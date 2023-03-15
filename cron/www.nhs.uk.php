<?php

require_once('../www/api/logging.php');
require_once('../www/api/mysql_api.php');

const URL = "https://www.nhs.uk";
const USERAGENT = "Mozilla/5.0 (X11; Linux x86_64; rv:91.0) Gecko/20100101 Firefox/91.0";
const INSERT_DISEASE_SQL = "INSERT INTO `mh_diseases` (`link`, `short_name`, `full_name`, `definition`) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE short_name=VALUES(short_name), full_name=VALUES(full_name), definition=VALUES(definition)";

$http_status_codes = include('../www/api/http-status-codes.php');
$logging = new logging(basename(__FILE__, ".php"));

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

function download($link, $replace_feature=false, $timeout = 15) {
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
		if ($replace_feature) {
			$results = preg_replace("/ data-block-key=\".*?\"/", '', $results);
			$results = preg_replace("/ class=\".*?\"/", '', $results);
			$results = str_replace(["\t", "\r", "\n"], '', $results);
			$results = str_replace('<a href=\"', '<a href=\"' . URL, $results);
		}
        $dom->loadHTML($results, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
	} else {
		$error_msg = curl_error($ch);
		log_write("curl error $link: $error_msg");
	}
	curl_close($ch);
	return $dom;
}

function save_html($element) {
	$element_html = $element->ownerDocument->saveHTML($element);
	$search = ['  ', '<section>', '</section>'];
	return str_replace($search, '', $element_html);
}

function download_diseases($links, $from, $to) {
	$conn = get_conn();
    for ($l=$from; $l < $to; $l++) {
		$link = $links[$l]->getAttribute('href');
		$full_link = URL . $link;
		$short_name = str_replace(["\t", "\r", "\n", '  '], '', $links[$l]->textContent);
		$full_name = $short_name;
		$names = explode(', see ', $short_name);
		if (isset($names[1])) {
			$short_name = $names[0];
			$full_name = $names[1];
		}
		$disease_dom = download($link, true);
		if (is_null($disease_dom))
			continue;
		$main_content = $disease_dom->getElementById('maincontent');
		$sections = $main_content->getElementsByTagName('section');
		if (!isset($sections[0])) {
			log_write("skipping $link: sections not found");
			continue; # seems like its cards or list of links or something other
		}
		$definition = save_html($sections[0]);
		$conn->logging_start = "full_name=$full_name";
        $conn->query(INSERT_DISEASE_SQL, "ssss", [
			$full_link,
			$short_name,
			$full_name,
			$definition
		]);
        $disease = $conn->get('mh_diseases', ['id'], [
            'link' => $full_link
        ]);
        if (!isset($disease[0]))
            continue;
        $disease_id = $disease[0]['id'];
        for ($s=1; $s < sizeof($sections); $s++) {
			$h2 = $sections[$s]->getElementsByTagName('h2');
			if (!isset($h2[0]))
				continue;
			$header = str_replace('  ', '', $h2[0]->textContent);
			// $sections[$s]->removeChild($sections[$s]->firstChild); // remove article name from content
            $mysql_by_columns = [
                'disease_id' => $disease_id,
                'header' => $header
            ];
            $disease_article = $conn->get('mh_disease_articles', ['id'], $mysql_by_columns);
            $mysql_columns = $mysql_by_columns;
            $mysql_columns['content'] = save_html($sections[$s]);
            if (isset($disease_article[0])) {
                $conn->set('mh_disease_articles', $mysql_columns, $mysql_by_columns);
            } else {
                $conn->add('mh_disease_articles', $mysql_columns);
            }
        }
	}
	$conn->close();
}

function remove_href_duplicates($links) {
	$unic_links = [];
	$new_links = [];
	$duplicates_count = 0;
	for ($l=0; $l < sizeof($links); $l++) {
		$link = $links[$l]->getAttribute('href');
		if ($link == "#nhsuk-nav-a-z")
			continue;
		if (in_array($link, $unic_links)) {
			$duplicates_count++;
			continue;
		}
		$unic_links[] = $link;
		$new_links[] = $links[$l];
	}
	if ($duplicates_count > 0)
		log_write(__FUNCTION__.": removed $duplicates_count duplicates");
	return $new_links;
}

try {
	$conditions = download('/conditions');
	$main_content = $conditions->getElementById('maincontent');
	$ol = $main_content->getElementsByTagName('ol')[1];
	$links = remove_href_duplicates($ol->getElementsByTagName('a'));
	$pages_count = sizeof($links) - 1; #( - 1) except last 'Back to top' hyperlink
	$processes_count = 1;
	$pages_per_process = $pages_count;
	// for ($i=1; $i > 0; $i--) {
	// 	$ppp = $pages_count / $i;
	// 	if (isset(explode('.', (string) $ppp, 2)[1]))
	// 		continue;
	// 	$pages_per_process = $ppp;
	// 	$processes_count = $i;
	// 	break;
	// }
	for ($p=0; $p <= $pages_count - 1; $p += $pages_per_process) {
		$from = $p;
		$to = $p + $pages_per_process;
		$pid = pcntl_fork();
		if (!$pid) { 
			$now_microtime = microtime(true);
			log_write("Started process for downloading $pages_per_process pages from $from to $to");
			download_diseases($links, $from, $to);
			$elapsed_seconds = round(microtime(true) - $now_microtime, 3);
			log_write("Downloaded $pages_per_process pages from $from to $to in $elapsed_seconds seconds");
			exit($p);
		}
	}
	while(pcntl_waitpid(0, $status) != -1){
		$status = pcntl_wexitstatus($status);
	}
} catch (Error $e) {
	log_write($e);
} catch (Exception $e) {
	log_write($e);
}
# document.getElementById('maincontent').getElementsByTagName('ol')[1].getElementsByTagName('a')[0]

