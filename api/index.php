<?php

require_once('medicalhub_api.php');
require_once('logging.php');

function api_response($data, $code) {
    global $logging;
    global $http_reponse_codes;
    $encoded_data = json_encode($data);
    http_response_code($code);
    echo $encoded_data;
    $http_request_method = strtoupper($_SERVER['REQUEST_METHOD']);
    $response_code = $http_reponse_codes[$code];
    $data_length = strlen($encoded_data);
    $request_uri = $_SERVER['REQUEST_URI'];
    $logging->write("\"$http_request_method $request_uri\" $code $response_code $data_length");
    exit(0);
}

function api_exec($on_request_methods, $user_login=false) {
    $http_request_method = strtolower($_SERVER['REQUEST_METHOD']);
    if (!isset($on_request_methods[$http_request_method])) 
        api_response(["error" => "unsupported_method"], 405);
    try {
        $user = null;
        if ($user_login) {
            $headers = getallheaders();
            if (!empty($headers['Authorization'])) {
                $access_token = $headers['Authorization'];
                $user = new User();
                $user->login($access_token);
            }
        }
        $data = json_decode(file_get_contents("php://input"));;
        $on_request_methods[$http_request_method]($data, $user);
    } catch (MedicalHubException $e) {
        api_response(["error" => $e->getMessage()], 400);
    } catch (Exception $e) {
        api_response(["error" => "server_error"], 500);
    }
}

// $logging = new logging(basename(__FILE__, '.php'));
$logging = new logging('api');
$http_reponse_codes = include('http-status-codes.php');
$api_objects = (object) array();
$api_object = $_REQUEST['object'];
$api_name = $_REQUEST['name'];

function get_api_name() {
    global $api_name;
    return $api_name;
}

$api_objects->user['register'] = function() {
    api_exec(['post' => function($data) {
        if (!isset($data->email, $data->password, $data->first_name, $data->last_name)) {
            api_response([
                "error" => "missing_parameters",
                "required_parameters" => [
                    "email", "password", "first_name", "last_name"
                ]
            ], 400);
        }

        $user = new User();
        $user->register($data->email, $data->password, $data->first_name, $data->last_name);
        api_response([], 200);
    }]);
};

$api_objects->user['login'] = function() {
    api_exec(['post' => function($data) {
        if (!isset($data->email, $data->password)) {
            api_response([
                "error" => "missing_parameters",
                "required_parameters" => ["email", "password"]
            ], 400);
        }
        
        $user = new User();
        $user_session = $user->authorize($data->email, $data->password);
        api_response([
            "access_token" => $user_session->access_token,
            "expired" => $user_session->expired
        ], 200);
    }]);
};

$api_objects->diseases['directory'] = function() {
    api_exec(['get' => function() {
        if (!isset($_GET['letter']))
            api_response([
                "error" => "missing_parameters",
                "required_parameters" => ["letter"]
            ], 400);
        $mh = new medicalhub_api();
        api_response($mh->get_diseases_directory($_GET['letter']), 200);
    }]);
};

$api_objects->diseases['popular'] = function() {
    api_exec(['get' => function() {
        $mh = new medicalhub_api();
        api_response($mh->get_popular_diseases(), 200);
    }]);
};

$api_objects->disease[''] = function() {
    api_exec(['get' => function() {
        $mh = new medicalhub_api();
        api_response($mh->get_disease(get_api_name()), 200);
    }]);
};

$api_objects->directions['list'] = function() {
    api_exec(['get' => function() {
        $mh = new medicalhub_api();
        api_response($mh->get_directions_list(), 200);
    }]);
};


$api_objects->doctors['list'] = function() {
    api_exec(['get' => function() {
        $mh = new medicalhub_api();
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 0;
        $direction_ids = isset($_GET['direction_ids']) ? explode(',', $_GET['direction_ids']) : [];
        $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';
        $search_by_full_name = isset($_GET['search_by_full_name']) ? $_GET['search_by_full_name'] : '';
        api_response($mh->get_doctors_list($page, $direction_ids, $sort_by, $search_by_full_name), 200);
    }]);
};

$api_objects->doctor[''] = function() {
    api_exec(['get' => function() {
        $mh = new medicalhub_api();
        api_response($mh->get_doctor(get_api_name()), 200);
    }]);
};

$api_objects->grade['questions'] = function() {
    api_exec(['get' => function() {
        $mh = new medicalhub_api();
        api_response($mh->get_grade_questions(), 200);
    }]);
};

$api_objects->grade['doctor'] = function() {
    api_exec(['get' => function($data, $user) {
        if (!isset($_GET['id']))
            api_response([
                "error" => "missing_parameters",
                "required_parameters" => ["id"]
            ], 400);
        $mh = new medicalhub_api();
        api_response($mh->get_grade_doctor($_GET['id'], $user->id), 200);
    }, 'post' => function($data, $user) {
        if (!isset($data->id, $data->question_answers)) {
            api_response([
                "error" => "missing_parameters",
                "required_parameters" => ["id", "question_answers"]
            ], 400);
        }
        $comment = isset($data->comment) ? $data->comment : '';
        $mh = new medicalhub_api();
        $mh->grade_doctor($data->id, $user->id, $comment, $data->question_answers);
        api_response([], 200);
    }], true);
};

try {
    if (isset($api_objects->$api_object)) {
        if (isset($api_objects->$api_object[$api_name])) {
            $api_objects->$api_object[$api_name]();
        } else {
            if (isset($api_objects->$api_object[''])) {
                $api_objects->$api_object['']();
            } else {
                api_response(["error" => "name_not_found"], 404);
            }
        }
    } else {
        api_response(["error" => "object_not_found"], 404);
    }
} catch (Exception|Error $e) {
    $logging->write($e);
    api_response(["error" => "server_error"], 500);
}