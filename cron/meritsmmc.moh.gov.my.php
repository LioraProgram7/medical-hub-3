<?php

require_once('../www/api/logging.php');
require_once('../www/api/mysql_api.php');

const URL = "https://meritsmmc.moh.gov.my";
const USERAGENT = "Mozilla/5.0 (X11; Linux x86_64; rv:91.0) Gecko/20100101 Firefox/91.0";
const INSERT_DOCTOR_SQL = "INSERT INTO `mh_doctors` (`link`, `full_name`, `qualification`, `graduated_of`, `provisional_registration_number`, `date_of_provisional_registration`, `full_registration_number`, `date_of_full_registration`, `timestamp`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE full_name=VALUES(full_name), qualification=VALUES(qualification), graduated_of=VALUES(graduated_of), provisional_registration_number=VALUES(provisional_registration_number),  date_of_provisional_registration=VALUES(date_of_provisional_registration), full_registration_number=VALUES(full_registration_number), date_of_full_registration=VALUES(date_of_full_registration)";

$http_status_codes = include('../www/api/http-status-codes.php');
$logging = new logging(basename(__FILE__, ".php"));

ini_set('memory_limit', '-1');
date_default_timezone_set("Europe/Vilnius");

function log_write($log_message) {
	global $logging;
	$logging->write($log_message);
}

function get_conn() {
	$mysql = new mysql();
	$connect_result = $mysql->connect();
	if ($connect_result === false)
		die();
	$mysql->query("SET character_set_results = 'utf8mb4', character_set_client = 'utf8mb4', character_set_connection = 'utf8mb4', character_set_database = 'utf8mb4', character_set_server = 'utf8mb4'");
	return $mysql;
}

$lastPage = 0;
$sc = download('/search/registeredDoctor?page=1');
$pages = allBetween('?page=', '"', $sc);
foreach ($pages as $page) {
	if ($page > $lastPage) {
		$lastPage = $page;
	}
}

function downloadItemsOnPage($page, $conn) {
	$scPage = download('/search/registeredDoctor?page=' . $page);
	$itemsOnPage = allBetween('/viewDoctor/', '/', $scPage);
	foreach ($itemsOnPage as $item) {
		$sc = download('/viewDoctor/' . $item . '/search');
		$r = array();
		$r['link'] = URL . '/viewDoctor/' . $item . '/search';
		$r['full_name'] = getField('Full Name', $sc);
		$r['qualification'] = getField('Qualification', $sc);
		$r['graduated_of'] = getField('Graduated of', $sc);
		$r['provisional_registration_number'] = getField('Provisional Registration Number', $sc);
		$r['date_of_provisional_registration'] = getField('Date of Provisional Registration ', $sc);
		$r['full_registration_number'] = getField('Full Registration Number', $sc);
		$r['date_of_full_registration'] = getField('Date of Full Registration', $sc);

		if (empty($r['full_name']))
			continue;
		if (substr_count($r['date_of_provisional_registration'], '-') == 2) {
			$dateEx = explode('-', $r['date_of_provisional_registration']);
			$r['date_of_provisional_registration'] = $dateEx[2] . '-' . $dateEx[1] . '-' . $dateEx[0];
		}
		if (substr_count($r['date_of_full_registration'], '-') == 2) {
			$dateEx = explode('-', $r['date_of_full_registration']);
			$r['date_of_full_registration'] = $dateEx[2] . '-' . $dateEx[1] . '-' . $dateEx[0];
		}

		$all = allBetween('<tr>', '</tr>', between(1, '<tbody>', '</tbody>', $sc));
		for ($i = 1; $i <= 3; $i++) {
			$r['apc_year_' . $i] = '';
			$r['apc_no_' . $i] = '';
			$r['place_of_practise_principle_first_' . $i] = '';
			$r['place_of_practise_principle_other_' . $i] = '';
			$r['place_of_practise_others_' . $i] = '';
			if (isset($all[$i - 1])) {
				$r['apc_year_' . $i] = between(2, '<td>', '</td>', $all[$i - 1]);
				$r['apc_no_' . $i] = between(3, '<td>', '</td>', $all[$i - 1]);
				$practise = between(4, '<td>', '</td>', $all[$i - 1]);
				$practiseEx = explode('<br/>', $practise);
				$r['place_of_practise_principle_first_' . $i] = removeSpaces($practiseEx[0]);
				for ($j = 1; $j < count($practiseEx); $j++) {
					$value = removeSpaces($practiseEx[$j]);
					if ($value != '') {
						if ($r['place_of_practise_principle_other_' . $i] == '') {
							$r['place_of_practise_principle_other_' . $i] .= $value;
						} else {
							$r['place_of_practise_principle_other_' . $i] .= ', ' . $value;
						}
					}
				}
				$practise = between(5, '<td>', '</td>', $all[$i - 1]);
				$practiseEx = explode('<br/>', $practise);
				for ($j = 1; $j < count($practiseEx); $j++) {
					$value = removeSpaces($practiseEx[$j]);
					if ($value != '') {
						if ($r['place_of_practise_others_' . $i] == '') {
							$r['place_of_practise_others_' . $i] .= $value;
						} else {
							$r['place_of_practise_others_' . $i] .= ', ' . $value;
						}
					}
				}
			}
		}

		foreach ($r as $key => $value) {
			if ($r[$key] == '-') {
				$r[$key] = '';
			}
		}

		insertUpdate($r, $conn);
	}
}

$processes_count = 6;
$ppp = (int) ($lastPage / $processes_count); //pages per process
$download_pages_count = $ppp * $processes_count;

for ($page = 1; $page <= $lastPage; $page += $ppp) {
	$page_from = $page;
	$page_to = $page_from + $ppp;

	if ($page_to > $lastPage)
		$page_to = $lastPage;

	$pid = pcntl_fork();

	if(!$pid){
		log_write("Started process for downloading pages from $page_from to $page_to");
		$now_microtime = microtime(true);
		$conn = get_conn();
		for ($p = $page_from; $p <= $page_to; ++$p) {
			downloadItemsOnPage($p, $conn);
		}
		$conn->close();
		$elapsed_seconds = round(microtime(true) - $now_microtime, 3);
		log_write("Downloaded pages from $page_from to $page_to in $elapsed_seconds seconds");
		exit($page);
	}
}

log_write("Started $processes_count processes for downloading $download_pages_count pages");
$now_microtime = microtime(true);

while(pcntl_waitpid(0, $status) != -1){
	$status = pcntl_wexitstatus($status);
}

$elapsed_seconds = round(microtime(true) - $now_microtime, 3);
log_write("Downloaded $download_pages_count in $processes_count processes in $elapsed_seconds seconds");

function insertUpdate($r, $conn)
{
	$conn->logging_start = "full_name=" . $r['full_name'];
	$conn->query(INSERT_DOCTOR_SQL, "ssssssss", [
		$r['link'],
		$r['full_name'],
		$r['qualification'],
		$r['graduated_of'],
		$r['provisional_registration_number'],
		$r['date_of_provisional_registration'],
		$r['full_registration_number'],
		$r['date_of_full_registration']		
	]);
	$doctor_id = $conn->get('mh_doctors', ['id'], ['link' => $r['link']])[0]['id'];
	for ($i=1; $i <= 3; $i++) {
		$conn->logging_start = "no=$i";
		$mysql_by_columns = ['doctor_id' => $doctor_id, 'no' => $i];
		$doctor_apc = $conn->get('mh_doctors_apc', ['apc_no', 'apc_year'], $mysql_by_columns);
		if (empty($r["apc_no_$i"]) && empty($r["apc_year_$i"])) {
			if (isset($doctor_apc[0]))
				$conn->delete('mh_doctors_apc', $mysql_by_columns);
			continue;
		}
		$mysql_stmt_types = "iisssss";
		$mysql_columns = [
			'doctor_id' => $doctor_id,
			'no' => $i,
			'apc_no' => $r["apc_no_$i"],
			'apc_year' => $r["apc_year_$i"],
			'place_of_practise_principle_first' => $r["place_of_practise_principle_first_$i"],
			'place_of_practise_principle_other' => $r["place_of_practise_principle_other_$i"],
			'place_of_practise_others' => $r["place_of_practise_others_$i"],	
		];
		if (isset($doctor_apc[0])) {
			$mysql_stmt_types .= 'ii';
			$conn->set('mh_doctors_apc', $mysql_columns, $mysql_by_columns, $mysql_stmt_types);
		} else {
			$conn->add('mh_doctors_apc', $mysql_columns, $mysql_stmt_types);
		}
	}
}

function getField($name, $sc)
{
	return removeSpaces(between(1, '<div class="col-sm-6">', '</div>', between(1, '>' . $name . '</label>', '</fieldset>', $sc)));
}

function download($link, $timeout = 15)
{
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
	if (!curl_errno($ch)) {
		$request = "\"GET $link\"";
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$status_code = $http_status_codes[$code];
		log_write("$request $code $status_code " . strlen($results) . " $elapsed_seconds s");	
	} else {
		$error_msg = curl_error($ch);
		log_write("curl error $link: $error_msg");
	}
	curl_close($ch);
	return $results;
}

function between($el, $from, $to, $sc)
{
	$temp1 = explode($from, $sc);
	$temp2 = explode($to, @$temp1[$el]);
	$temp = $temp2[0];
	return $temp;
}

function allBetween($from, $to, $sc)
{
	$result = array();
	$temp1 = explode($from, $sc);
	for ($i = 1; $i < count($temp1); $i++) {
		$temp2 = explode($to, $temp1[$i]);
		$temp = $temp2[0];
		$result[] = $temp;
	}
	return $result;
}

function removeSpaces($text)
{
	$text = preg_replace('/\s+/', ' ', $text);
	$text = trim($text);
	return $text;
}