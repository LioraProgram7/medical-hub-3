<?php
require_once('mysql_api.php');

class MedicalHubException extends Exception { };

class medicalhub_api {
    public $mysql;

    function __construct($user_id=null, $mysql=null) {
        if (is_null($mysql)) {
            $this->mysql = new mysql($user_id);
            $this->mysql->connect();
        }
    }

    function get_diseases_directory($letter) {
        $letters = str_split('0' . join("", range('A', 'Z')));
        if (!in_array($letter, $letters))
            throw new MedicalHubException('invalid_letter');
        $command = "SELECT id, short_name FROM mh_diseases WHERE ";
        if ($letter != '0') {
            $command .= "short_name LIKE ? ORDER BY `short_name` ASC";
            return mysqli_fetch_all($this->mysql->query($command, 's', ["$letter%"]), MYSQLI_ASSOC);
        }
        $numbers = range('0', '9');
        $values = [];
        for ($i=0; $i < sizeof($numbers); $i++) {
            $command .= "short_name LIKE ? OR ";
            $values[] = $numbers[$i] . '%';
        }
        $command = rtrim($command, ' OR ');
        $command .= " ORDER BY `short_name` ASC";
        $stmt_types = str_repeat("s", sizeof($values));
        return mysqli_fetch_all($this->mysql->query($command, $stmt_types, $values), MYSQLI_ASSOC);
    }
    
    function get_popular_diseases($count=20) {
        $command = "SELECT id, short_name FROM mh_diseases WHERE views > 0 ORDER BY views DESC LIMIT 0, ?";
        $query_result = $this->mysql->query($command, 'i', [$count]);
        return mysqli_fetch_all($query_result, MYSQLI_ASSOC);
    }

    function get_disease($id) {
        $diseases = $this->mysql->get('mh_diseases', ['id', 'short_name', 'full_name', 'definition'], ['id' => $id]);
        if (!isset($diseases[0])) 
            throw new MedicalHubException('disease_not_found');
        $disease_articles = $this->mysql->get('mh_disease_articles', ['id', 'header', 'content'], [
            'disease_id' => $id
        ]);
        $disease = $diseases[0];
        $disease['articles'] = $disease_articles;
        $this->mysql->query("UPDATE mh_diseases SET views=views + 1 WHERE id=?", 'i', [$id]); // add one view
        return $disease;
    }

    function get_directions_list() {
        return $this->mysql->get('mh_directions', [
            'id', 'name', 'specialization_name', 'doctors_count', 'img_link'
        ]);
    }

    private function get_doctor_ids($direction_ids) {
        $in = rtrim(str_repeat('?, ', sizeof($direction_ids)), ', ');
        $command = "SELECT doctor_id FROM mh_doctors_directions WHERE direction_id IN ($in)";
        $stmt_types = str_repeat('i', sizeof($direction_ids));
        $query_result = $this->mysql->query($command, $stmt_types, $direction_ids);
        $doctors_directions = mysqli_fetch_all($query_result, MYSQLI_ASSOC);
        return array_values(array_column($doctors_directions, 'doctor_id'));
    }

    //SELECT AVG(ga.score) as avg_score, ROUND(AVG(ga.score)) as round_avg_score, gq.text, ga.text FROM mh_grade_questions gq
    // INNER JOIN mh_grade_answers ga ON gq.id = ga.question_id
    // INNER JOIN mh_doctor_grade_answers dga ON ga.id = dga.grade_answer_id
    // INNER JOIN mh_doctor_grades dg ON dga.doctor_grade_id = dg.id WHERE dg.doctor_id = 1 AND ga.score IS NOT NULL
    // GROUP BY gq.id, ga.question_id
    
    function get_doctors_list($page, $direction_ids=[], $sort_by='', $search_by_full_name='') {
        // $logging = new logging('mh_api');
        $page_columns_count = 6;
        $sort_by_params = ['name_asc', 'name_desc', 'lastname_asc', 'lastname_desc', 'rate_up', 'rate_down'];
        $command = "SELECT id, full_name, graduated_of FROM `mh_doctors` ";
        $stmt_types = '';
        $values = [];
        $columns_from = $page_columns_count * $page;
        $columns_to = $page_columns_count;
        if (!empty($direction_ids)) {
            $doctor_ids = $this->get_doctor_ids($direction_ids);
            if (empty($doctor_ids))
                return ['doctors_count' => 0, 'pages_count' => 0, 'list' => []];
            $in = rtrim(str_repeat('?, ', sizeof($doctor_ids)), ', ');
            $command .= "WHERE id IN ($in)";
            $stmt_types .= str_repeat('i', sizeof($doctor_ids));
            $values = $doctor_ids;
        }
        if (!empty($search_by_full_name)) {
            $command .= empty($direction_ids) ? "WHERE" : " AND";
            $command .= " full_name LIKE ? ";
            $stmt_types .= 's';
            $values[] = "%$search_by_full_name%";
        }
        if (!empty($sort_by)) {
            if (!in_array($sort_by, $sort_by_params))
                throw new MedicalHubException('invalid_sort_by');
            $command .= " ORDER BY ";
            if (in_array($sort_by, ['name_asc', 'name_desc'])) {
                $command .= 'full_name ';
                $command .= $sort_by === 'name_asc' ? 'ASC' : 'DESC';
            } elseif (in_array($sort_by, ['lastname_asc', 'lastname_desc'])) {
                $command .= "SUBSTRING_INDEX(SUBSTRING_INDEX(full_name, ' ', 2), ' ', -1) ";
                $command .= $sort_by === 'lastname_asc' ? 'ASC' : 'DESC';
            } else {
                throw new MedicalHubException('sort_by_rate_unavailable');
            }
            $command .= ' ';
            if ($page < 1 && in_array($sort_by, ['name_desc', 'lastname_desc']))
                $columns_from = 1; // idk but it only works like this
        }
        $command = str_replace('  ', ' ', $command);
        $columns_count = mysqli_fetch_all(
            $this->mysql->query(
                str_replace('id, full_name, graduated_of', 'COUNT(id) AS columns_count', $command), 
                !empty($stmt_types) ? $stmt_types : null , 
                !empty($values) ? $values : null
            ), MYSQLI_ASSOC
        )[0]['columns_count'];
        $command .= "LIMIT ?, ?";
        array_push($values, (int) $columns_from, (int) $columns_to);
        $stmt_types .= str_repeat('i', 2);
        $doctors = mysqli_fetch_all($this->mysql->query($command, $stmt_types, $values), MYSQLI_ASSOC);
        $pages_count = sizeof(array_chunk(range(0, $columns_count), $page_columns_count)) - 1;
        $doctor_ids = array_column($doctors, 'id');
        if (!empty($doctor_ids)) {
            $in = "IN (" . rtrim(str_repeat('?, ', sizeof($doctor_ids)), ', ') . ")";
            $stmt_types = str_repeat('i', sizeof($doctor_ids));
            
            $command = "SELECT 
                `doctor_id`, 
                `place_of_practise_principle_first` 
            FROM `mh_doctors_apc` WHERE doctor_id $in AND no = 1";
            $doctors_apc = mysqli_fetch_all($this->mysql->query($command, $stmt_types, $doctor_ids), MYSQLI_ASSOC);
            
            $command = "SELECT 
                dd.doctor_id, 
                dd.direction_id, 
                d.specialization_name 
            FROM mh_doctors_directions dd 
            INNER JOIN mh_directions d ON d.id = dd.direction_id
            WHERE dd.doctor_id $in";
            $doctors_directions = mysqli_fetch_all($this->mysql->query($command, $stmt_types, $doctor_ids), MYSQLI_ASSOC);

            for ($d=0; $d < sizeof($doctors); $d++) {
                $doctors[$d]['work_in'] = '';
                $doctors[$d]['directions'] = [];
                for ($a=0; $a < sizeof($doctors_apc); $a++) 
                    if ($doctors[$d]['id'] === $doctors_apc[$a]['doctor_id'])
                        $doctors[$d]['work_in'] = $doctors_apc[$a]['place_of_practise_principle_first'];
                for ($dd=0; $dd < sizeof($doctors_directions); $dd++)
                    if ($doctors_directions[$dd]['doctor_id'] === $doctors[$d]['id'])
                        $doctors[$d]['directions'][] = $doctors_directions[$dd]['specialization_name'];
            }
        }
        // $logging->write("doctors " . json_encode($doctors));
        return [
            'doctors_count' => $columns_count,
            'pages_count' => $pages_count,
            'list' => $doctors
        ];
    }

    function get_doctor($id) {
        $doctors = $this->mysql->get('mh_doctors', [
            'full_name', 'qualification', 'graduated_of', 'provisional_registration_number', 'date_of_provisional_registration', 'full_registration_number', 'date_of_full_registration'
        ], ['id' => $id]);
        if (!isset($doctors[0]))
            throw new MedicalHubException("doctor_not_found");
        $doctor = $doctors[0];
        $doctors_apc = $this->mysql->get('mh_doctors_apc', [
            'no', 'apc_no', 'apc_year', 'place_of_practise_principle_first'
        ], [
            'doctor_id' => $id
        ]);
        $directions = $this->mysql->get('mh_directions', ['id', 'specialization_name']);
        $doctor_directions = $this->mysql->get('mh_doctors_directions', ['direction_id'], ['doctor_id' => $id]);
        $doctor_direction_ids = array_column($doctor_directions, 'direction_id');
        $doctor['apc'] = $doctors_apc;
        $doctor['directions'] = [];
        for ($d=0; $d < sizeof($directions); $d++)
            if (in_array($directions[$d]['id'], $doctor_direction_ids))
                $doctor['directions'][] = $directions[$d];
        
        $command = "SELECT 
            ROUND(AVG(mh_doctor_grades.is_recommended) * 100) AS rp
        FROM mh_doctor_grades WHERE doctor_id = ?";

        $recommends_percent = mysqli_fetch_all($this->mysql->query($command, 'i', [$id]), MYSQLI_ASSOC)[0]['rp'];

        $command = "SELECT 
            COUNT(*) AS grades_count 
        FROM mh_doctor_grade_answers dga
        INNER JOIN mh_doctor_grades dg ON dg.id = dga.doctor_grade_id WHERE dg.doctor_id = ?
        GROUP BY dga.user_id";

        $grades_count = mysqli_num_rows($this->mysql->query($command, 'i', [$id]));

        $command = "SELECT 
            COUNT(dg.comment) as rc
        FROM mh_doctor_grades dg WHERE dg.comment != '' AND dg.comment IS NOT NULL AND dg.doctor_id = ?";

        $reviews_count = mysqli_fetch_all($this->mysql->query($command, 'i', [$id]), MYSQLI_ASSOC)[0]['rc'];

        $doctor['recommends_percent'] = (is_null($recommends_percent) ? 0 : $recommends_percent) . '%';
        $doctor['reviews_count'] = $reviews_count;
        $doctor['grades_count'] = $grades_count;

        $command = "SELECT 
            AVG(ga.score) as avg_score, 
            gq.text as question_text, 
            gq.id as question_id
        FROM mh_grade_questions gq
        INNER JOIN mh_grade_answers ga ON gq.id = ga.question_id
        INNER JOIN mh_doctor_grade_answers dga ON ga.id = dga.grade_answer_id
        INNER JOIN mh_doctor_grades dg ON dga.doctor_grade_id = dg.id WHERE dg.doctor_id = ? AND ga.score IS NOT NULL
        GROUP BY gq.id, ga.question_id";

        $grade_questions = mysqli_fetch_all($this->mysql->query($command, 'i', [$id]), MYSQLI_ASSOC);
        $grade_answers = $this->mysql->get('mh_grade_answers', [mysql::COLUMN_ALL]);
        for ($q=0; $q < sizeof($grade_questions); $q++) {
            for ($a=0; $a < sizeof($grade_answers); $a++) {
                if ($grade_questions[$q]['question_id'] != $grade_answers[$a]['question_id'])
                    continue;
                if (round($grade_questions[$q]['avg_score']) != $grade_answers[$a]['score'])
                    continue;
                $grade_questions[$q]['avg_score'] = round($grade_questions[$q]['avg_score'], 1);
                $grade_questions[$q]['answer_text'] = $grade_answers[$a]['text'];
                break;
            }
        } 
        $doctor['grade_stats'] = $grade_questions;
        
        $command = "SELECT first_name, comment, date, is_recommended 
        FROM mh_users 
        INNER JOIN mh_doctor_grades ON mh_users.id = mh_doctor_grades.user_id 
        WHERE mh_doctor_grades.doctor_id = ? AND 
        mh_doctor_grades.comment IS NOT NULL AND 
        mh_doctor_grades.comment <> ''";

        $doctor['reviews'] = mysqli_fetch_all($this->mysql->query($command, 'i', [$id]), MYSQLI_ASSOC);
        return $doctor; 
    }

    function get_grade_questions() {
        $questions = $this->mysql->get('mh_grade_questions', [mysql::COLUMN_ALL]);
        $answers = $this->mysql->get('mh_grade_answers', [mysql::COLUMN_ALL]);
        for ($q=0; $q < sizeof($questions); $q++) {
            $questions[$q]['answers'] = [];
            for ($a=0; $a < sizeof($answers); $a++) {
                if ($questions[$q]['id'] != $answers[$a]['question_id'])
                    continue;
                $questions[$q]['answers'][] = [
                    'id' => $answers[$a]['id'],
                    'text' => $answers[$a]['text']
                ];
            }
        }
        return $questions;
    }

    private function duplicates_in_array($array) {
        $counts = array_count_values($array);
        foreach ($counts as $value => $count)
            if ($count > 1) 
                return true;
        return false;
    }

    function get_grade_doctor($doctor_id, $user_id) {
        $doctors = $this->mysql->get('mh_doctors', ['id'], ['id' => $doctor_id]);
        if (!isset($doctors[0]))
            throw new MedicalHubException("doctor_not_found");
        $doctor_grades = $this->mysql->get('mh_doctor_grades', ['id', 'comment'], [
            'user_id' => $user_id,
            'doctor_id' => $doctor_id
        ]);
        if (!isset($doctor_grades[0]))
            throw new MedicalHubException("grade_not_found");
        $doctor_grade_answers = $this->mysql->get('mh_doctor_grade_answers', ['grade_answer_id'], [
            'user_id' => $user_id,
            'doctor_grade_id' => $doctor_grades[0]['id']
        ]);
        return [
            "answer_ids" => array_column($doctor_grade_answers, 'grade_answer_id'),
            "comment" => $doctor_grades[0]['comment']
        ];
    }

    function grade_doctor($doctor_id, $user_id, $comment, $question_answers) {
        $doctors = $this->mysql->get('mh_doctors', ['id'], ['id' => $doctor_id]);
        if (!isset($doctors[0]))
            throw new MedicalHubException("doctor_not_found");
        if (empty($question_answers))
            throw new MedicalHubException("empty_question_answers");
        $grade_questions = $this->get_grade_questions();
        $question_ids = array_column($grade_questions, 'id');
        if (sizeof($question_ids) != sizeof($question_answers) ||
            $this->duplicates_in_array(array_column($question_answers, 'question_id')))
            throw new MedicalHubException("invalid_question_answers");
        for ($q=0; $q < sizeof($question_answers); $q++) {
            if (!isset($question_answers[$q]->question_id))
                throw new MedicalHubException("missing_parameter_question_id");
            if (!isset($question_answers[$q]->answer_id))
                throw new MedicalHubException("missing_parameter_answer_id");
            $question_id = $question_answers[$q]->question_id;
            if (!in_array($question_id, $question_ids))
                throw new MedicalHubException("invalid_question_id");
            $answer_id = $question_answers[$q]->answer_id;
            for ($g=0; $g < sizeof($grade_questions); $g++) {
                if ($question_id != $grade_questions[$g]['id'])
                    continue;
                $answer_ids = array_column($grade_questions[$g]['answers'], 'id');
                if (!in_array($answer_id, $answer_ids))
                    throw new MedicalHubException("invalid_answer_id");
            }
        }
        $doctor_grades = $this->mysql->get('mh_doctor_grades', ['id'], [
            'user_id' => $user_id,
            'doctor_id' => $doctor_id
        ]);
        $is_recommended = $question_answers[0]->answer_id == 1 ? 1 : 0;
        if (isset($doctor_grades[0])) {
            $this->mysql->set('mh_doctor_grades', [
                'comment' => $comment,
                'is_recommended' => $is_recommended
            ], [
                'user_id' => $user_id,
                'doctor_id' => $doctor_id,
            ]); 
            $doctor_grade_id = $doctor_grades[0]['id'];
        } else {
            $this->mysql->add('mh_doctor_grades', [
                'user_id' => $user_id,
                'doctor_id' => $doctor_id,
                'comment' => $comment,
                'is_recommended' => $is_recommended
            ]);
            $doctor_grade_id = $this->mysql->get_connection()->insert_id;
        }       
        for ($q=0; $q < sizeof($question_answers); $q++) {
            isset($doctor_grades[0]) ?
            $this->mysql->set('mh_doctor_grade_answers', [
                'grade_answer_id' => $question_answers[$q]->answer_id
            ], [
                'user_id' => $user_id,
                'doctor_grade_id' => $doctor_grade_id,
                'grade_question_id' => $question_answers[$q]->question_id
            ]) :
            $this->mysql->add('mh_doctor_grade_answers', [
                'user_id' => $user_id,
                'doctor_grade_id' => $doctor_grade_id,
                'grade_question_id' => $question_answers[$q]->question_id,
                'grade_answer_id' => $question_answers[$q]->answer_id
            ]);
        }
    }
}

function sha512($string, $salt=null) {
    if (is_null($salt))
        $salt = 'm3d1c@lhub';
    return hash('sha512', $string . $salt);
}

function is_expired($timestamp) {
    return $timestamp < date('Y-m-d H:i:s');
}

//create new user session
class user_session {
    public $access_token;
    public $access_token_hash;
    public $expired;

    function __construct($mysql, $user_id, $expires_in=86400 /* 24 hours */) {
        $this->access_token = bin2hex(random_bytes(48));
        $this->access_token_hash = sha512($this->access_token);
        $this->expired = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . "+ $expires_in seconds"));
        //insert new user session
        $mysql->add('mh_user_sessions', [
            'user_id' => $user_id,
            'access_token' => $this->access_token_hash,
            'expired' => $this->expired
        ]);
    }
}

class user { 
    public $mysql;

    public $id;
    public $email;
    public $first_name;
    public $last_name;

    public $is_logged_in;

    function __construct($id=null, $mysql=null) {
        if (is_null($mysql)) {
            $this->mysql = new mysql($id);
            $this->mysql->connect();
        }
        $this->is_logged_in = false;
    }

    private function check_email($email) {
        if (empty($email))
            throw new MedicalHubException("empty_email");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 99)
            throw new MedicalHubException("invalid_email");
        $user = $this->mysql->get('mh_users', ['id'], ['email' => $email]);
        if (isset($user[0]))
            throw new MedicalHubException('email_exist');
    }

    private function check_password($password) {
        if (empty($password))
            throw new MedicalHubException('empty_password');
        if (strlen($password) > 100)
            throw new MedicalHubException('invalid_password');
    }

    private function check_names($first_name=null, $last_name=null) {
        if (!is_null($first_name)) {
            if (empty($first_name))
                throw new MedicalHubException('empty_first_name');
            if (strlen($first_name) > 40)
                throw new MedicalHubException('invalid_first_name');
        }
        if (!is_null($last_name)) {
            if (empty($last_name))
                throw new MedicalHubException('empty_last_name');
            if (strlen($last_name) > 40)
                throw new MedicalHubException('invalid_last_name');
        }
    }

    function register($email, $password, $first_name, $last_name) {
        $this->check_names($first_name, $last_name);
        $this->check_email($email);
        $this->check_password($password);
        $this->mysql->add('mh_users', [
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'auth_method' => 'normal'
        ]);
        $this->id = $this->mysql->get_connection()->insert_id;
        $password_hash = sha512($password, $this->id);
        $this->mysql->set('mh_users', ['password' => $password_hash], [
            'id' => $this->id
        ]);
    }

    function authorize($email, $password) {
        $user = $this->mysql->get('mh_users', ['id', 'email', 'password'], [
            'email' => $email
        ]);
        if (!isset($user[0]))
            throw new MedicalHubException('user_not_found');
        $password_hash = sha512($password, $user[0]['id']);
        $this->is_logged_in = $password_hash === $user[0]['password'];
        if (!$this->is_logged_in)
            throw new MedicalHubException('incorrect_password');
        $this->id = $user[0]['id'];
        return new user_session($this->mysql, $this->id);
    }
    function login($access_token) {
        $access_token_hash = sha512($access_token);
        $user_session = $this->mysql->get('mh_user_sessions', ['user_id', 'expired'], [
            'access_token' => $access_token_hash
        ]);
        if (!isset($user_session[0]))
            throw new MedicalHubException('session_not_found');
        if (is_expired($user_session[0]['expired']))
            throw new MedicalHubException('session_expired');
        $this->is_logged_in = true;
        $this->id = $user_session[0]['user_id'];
        $user_account = $this->mysql->get('mh_users', ["email"], [
            'id' => $this->id
        ]);
        $this->email = $user_account[0]['email'];
    }
}
