<?php
require_once('../www/api/logging.php');
require_once('../www/api/mysql_api.php');

const SELECT_DOCTORS_APC = "SELECT * FROM `mh_doctors_apc` WHERE place_of_practise_principle_first LIKE ? OR place_of_practise_principle_other LIKE ? OR place_of_practise_others LIKE ?";

$logging = new logging(basename(__FILE__, ".php"));
$mysql = new mysql();
$connect_result = $mysql->connect();

if ($connect_result === false)
	die();

ini_set('memory_limit', '-1');

function log_write($log_message) {
	global $logging;
	$logging->write($log_message);
}

$directions = $mysql->get('mh_directions', ['id', 'name']);

for ($d=0; $d < sizeof($directions); $d++) {
    $now_microtime = microtime(true);
    $direction_keywords = $mysql->get('mh_direction_keywords', ['keyword'], [
        'direction_id' => $directions[$d]['id']
    ]);
    $command = "SELECT * FROM `mh_doctors_apc` WHERE ";
    $values = [];
    for ($k=0; $k < sizeof($direction_keywords); $k++) {
        $command .= "place_of_practise_principle_first LIKE ? OR ";
        $command .= "place_of_practise_principle_other LIKE ? OR ";
        $command .= "place_of_practise_others LIKE ? OR ";
        for ($i=0; $i < 3; $i++)
            $values[] = '% ' . $direction_keywords[$k]['keyword'] . '%';
    }
    $command = rtrim($command, ' OR ');
    $stms_types = str_repeat('s', sizeof($values));
    $query_result = $mysql->query($command, $stms_types, $values);
    $doctors_apc = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
    $doctor_ids = array_values(array_unique(array_column($doctors_apc, 'doctor_id')));
    for ($i=0; $i < sizeof($doctor_ids); $i++) {
        $mysql_columns = [
            'direction_id' => $directions[$d]['id'],
            'doctor_id' => $doctor_ids[$i]
        ];
        // log_write("d[$d]:". $directions[$d]['id'] . " i[$i]:" . $doctor_ids[$i]);
        $doctor_directions = $mysql->get('mh_doctors_directions', ['direction_id'], $mysql_columns, 'ii');
        if (isset($doctor_directions[0]))
            continue;
        $mysql->add('mh_doctors_directions', $mysql_columns, 'ii');
    }
    $results_count = sizeof($doctor_ids);
    $mysql->set('mh_directions', ['doctors_count' => $results_count], ['id' => $directions[$d]['id']]);
    $names = str_replace('%', '', implode(',', array_unique($values)));
    $elapsed_seconds = round(microtime(true) - $now_microtime, 3);
    log_write("Sorted $results_count doctors apc with $names directions in $elapsed_seconds seconds");
}