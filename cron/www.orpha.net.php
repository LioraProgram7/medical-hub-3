<?php

require_once('../www/api/logging.php');
require_once('../www/api/mysql_api.php');

const URL = "https://www.orpha.net";
const USERAGENT = "Mozilla/5.0 (X11; Linux x86_64; rv:91.0) Gecko/20100101 Firefox/91.0";
const INSERT_DISEASE_SQL = "INSERT INTO `mh_diseases` (`link`, `short_name`, `full_name`, `definition`) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE short_name=VALUES(short_name), full_name=VALUES(full_name), definition=VALUES(definition)";

$http_status_codes = include('../www/api/http-status-codes.php');
$logging = new logging(basename(__FILE__, ".php"));
$alphabet = str_split("0" . join("", range('A', 'Z')));

ini_set('memory_limit', '-1');

function log_write($log_message) {
	global $logging;
	$logging->write($log_message);
}

function get_conn() {
	$mysql = new mysql();
	$connect_result = $mysql->connect();
	if ($connect_result === false)
		die();
	return $mysql;
}

function download($link, $timeout = 60) {
	global $http_status_codes;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, USERAGENT);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_URL, URL . $link);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	$now_microtime = microtime(true);
	$results = curl_exec($ch);
	$elapsed_seconds = round(microtime(true) - $now_microtime, 3);
    $dom = new DOMDocument();
	if (!curl_errno($ch)) {
		$request = "\"GET $link\"";
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$status_code = $http_status_codes[$code];
		log_write("$request $code $status_code " . strlen($results) . " $elapsed_seconds s");	
        $dom->loadHTML($results);
	} else {
		$error_msg = curl_error($ch);
		log_write("curl error $link: $error_msg");
	}
	curl_close($ch);
	return $dom;
}

class article {
    public $header;
    public $content;
    function __construct($header, $content) {
        $this->header = $header;
        $this->content = "<h2>$header</h2><p>$content</p>";
    }
}

class exp {
    public $full_name;
    public $definition;
    public $articles;
    public $error=false;
    function __construct($link) {
        $dom_exp = download($link);
        $content_type = $dom_exp->getElementById('ContentType');
        if ($content_type === null) {
            $this->error = true;
            return;
        }
        $divs = $content_type->getElementsByTagName('div');
        $definition = new DOMDocument();
        $article_info = new DOMDocument();
        for ($d=0; $d < sizeof($divs); $d++) {
            $classname = $divs[$d]->getAttribute('class');
            if ($classname == 'definition')
                $definition = $divs[$d];
            if ($classname == 'articleInfo')
                $article_info = $divs[$d];
        }
        $section = $definition->getElementsByTagName('section')[0];
        $this->full_name = $content_type->getElementsByTagName('h2')[2]->textContent;
        if (is_null($section))
            $this->definition = "";
        else
            $this->definition = '<p>' . $section->getElementsByTagName('p')[0]->textContent . '</p>';
        $this->articles = [];
        $headers = $article_info->getElementsByTagName('h3');
        $contents = $article_info->getElementsByTagName('section');
        if (is_null($headers) || is_null($contents))
            return;
        for ($i=0; $i < sizeof($headers); $i++) {
            $header = $headers[$i]->textContent;
            $content = $contents[$i]->textContent;
            $this->articles[] = new article($header, $content);
        }
    }
}

function get_search_list($li) {
    $search_list = [];
    $links = [];
    $duplicates_links_count = 0;
    for ($l=0; $l < sizeof($li); $l++) {
        $a = $li[$l]->getElementsByTagName('a')[0];
        $link = URL . "/consor/cgi-bin/" . $a->getAttribute('href');
        $short_name = $a->textContent;
        if (in_array($link, $links)) {
            $duplicates_links_count++;
            continue;
        }
        $sl = new stdClass;
        $sl->link = $link;
        $sl->short_name = $short_name;
        $search_list[] = $sl;
        $links[] = $link;
    }
    if ($duplicates_links_count > 0)
        log_write(__FUNCTION__ . ": found $duplicates_links_count duplicates");
    return $search_list;
}

function download_diseases($search_list, $from, $to) {
    $conn = get_conn();
    for ($s=$from; $s < $to; $s++) {
        $exp = new exp(str_replace(URL, '', $search_list[$s]->link));
        if ($exp->error)
            continue;
        $conn->logging_start = "full_name=" . $exp->full_name;
        $conn->query(INSERT_DISEASE_SQL, "ssss", [
            $search_list[$s]->link,
            $search_list[$s]->short_name,
            $exp->full_name,
            $exp->definition
        ]);
        $disease = $conn->get('mh_diseases', ['id'], [
            'link' => $search_list[$s]->link
        ]);
        if (!isset($disease[0]))
            continue;
        $disease_id = $disease[0]['id'];
        for ($e=0; $e < sizeof($exp->articles); $e++) {
            $mysql_by_columns = [
                'disease_id' => $disease_id,
                'header' => $exp->articles[$e]->header
            ];
            $disease_article = $conn->get('mh_disease_articles', ['id'], $mysql_by_columns);
            $mysql_columns = $mysql_by_columns;
            $mysql_columns['content'] = $exp->articles[$e]->content;
            if (isset($disease_article[0])) {
                $conn->set('mh_disease_articles', $mysql_columns, $mysql_by_columns);
            } else {
                $conn->add('mh_disease_articles', $mysql_columns);
            }
        }
    }
    $conn->close();
}

$li = [];

for ($a=0; $a < sizeof($alphabet); $a++) {
    $dom_search = download("/consor/cgi-bin/Disease_Search_List.php?lng=EN&TAG=".$alphabet[$a]);
    $result_box = $dom_search->getElementById('result-box');
    if (is_null($result_box))
        continue;
    $li_elements = $result_box->getElementsByTagName('li');
    for ($l=0; $l < sizeof($li_elements); $l++)
        $li[] = $li_elements[$l];
}

$search_list = get_search_list($li);
$pages_count = sizeof($search_list);
$processes_count = 4;
$pages_per_process = (int) ($pages_count / $processes_count);
// for ($i = 9; $i > 0; $i--) {
//     $ppp = $pages_count / $i;
//     if (isset(explode('.', (string) $ppp, 2)[1]))
//         continue;
//     $pages_per_process = $ppp;
//     $processes_count = $i;
//     break;
// }
for ($p = 0; $p <= $pages_count - 1; $p += $pages_per_process) {
    $from = $p;
    $to = $p + $pages_per_process;
    if ($to > $pages_count)
        $to = $pages_count;
    $pid = pcntl_fork();
    if (!$pid) {
        $now_microtime = microtime(true);
        log_write("Started process for downloading $pages_count pages from $from to $to");
        download_diseases($search_list, $from, $to);
        $elapsed_seconds = round(microtime(true) - $now_microtime, 3);
        log_write("Downloaded $pages_count pages from $from to $to in $elapsed_seconds seconds");
        exit($p);
    }
}
while (pcntl_waitpid(0, $status) != -1) {
    $status = pcntl_wexitstatus($status);
}