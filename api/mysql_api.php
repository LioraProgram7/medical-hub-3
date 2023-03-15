<?php
require_once("logging.php");

class mysql {
    const DB_SERVER_NAME = "localhost";
    const DB_NAME = "l95243_mail";
    const DB_USERNAME = "l95243_mail";
    const DB_PASSWORD = "ebsxyr8bvelcewb0et";

    const COLUMN_ALL = "*";

    public $user_id;
    public $logging_start;
    protected $logging;
    protected $connection;

    public function __construct($user_id=null, $logging_start='') {
        if (!is_null($user_id))
            $this->user_id = $user_id;
        $this->logging_start = $logging_start;
        $this->logging = new logging(basename(__FILE__, ".php"));
    }

    public function connect($db=null) {   
        if (is_null($db))
            $db = mysql::DB_NAME;
        $servername = mysql::DB_SERVER_NAME;
        $username = mysql::DB_USERNAME;
        $password = mysql::DB_PASSWORD;
        $this->connection = new mysqli($servername, $username, $password, $db);
        if ($this->connection->connect_error) {
            $error = $this->connection->connect_error;
            $port = ini_get("mysqli.default_port");
            $this->logging->write("connect error to $servername:$port to database $db: $error");
            return false;
        }
        return true;
    }

    public function close() { $this->connection->close(); }

    public function get_connection() { return $this->connection; }

    public function set_connection($connection) { $this->connection = $connection; }

    public function query($command, $stmt_types=null, $values=null) {
        $log_message = "";
        $limit_len = 1000; //52
        $partial_command = substr($command, 0, $limit_len);
        if (strlen($command) > $limit_len)
            $partial_command .= '...';
        if (!empty($this->logging_start))
            $log_message .= $this->logging_start . " ";
        if (isset($this->user_id)) 
            $log_message .= "user_id=" . $this->user_id . " ";
        $log_message .= "\"$partial_command\" ";
        $stmt = $this->connection->prepare($command);
        if ($stmt === false) {
            $this->logging->write($log_message . "prepare error");
            return false;
        }
        if ($stmt_types !== null) {
            $bind_result = $stmt->bind_param($stmt_types, ...$values);
            if ($bind_result === false) {
                $this->logging->write($log_message . "bind_param error");
                return false;
            }
        }
        $exec_result = $stmt->execute();
        $log_message .= $exec_result !== false ? "OK" : "ERROR: " . $this->connection->error;
        // $this->logging->write($log_message);
        if ($exec_result === false) {
            $this->logging->write($log_message);
            return false;
        }
        return $stmt->get_result();
    }

    public function get($table, $columns, $by_columns=null, $stmt_types=null) {
        $command = "SELECT ";
        if ($columns[0] != mysql::COLUMN_ALL) {
            for ($i=0; $i < sizeof($columns); $i++)
                $command .= "`$columns[$i]`,";
            $command = rtrim($command, ",");
        } else {
            $command .= mysql::COLUMN_ALL;
        }
        // $command .= " FROM ";
        // for ($i=0; $i < sizeof($tables); $i++) 
        //     $command .= "`$tables[$i]`,";
        // $command = rtrim($command, ",");
        $command .= " FROM `$table`";
        if (!is_null($by_columns)) {
            $command .= " WHERE ";
            foreach($by_columns as $key => $value) {
                $command .= "`$key`=(?) AND ";
                $values[] = $value;
            }
            $command = rtrim($command, " AND ");
            if (is_null($stmt_types))
                $stmt_types = str_repeat("s", sizeof($values));
            $result = $this->query($command, $stmt_types, $values);
        } else {
            $result = $this->query($command);
        }
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function set($table, $columns, $by_columns, $stmt_types=null) {
        // $command = "UPDATE ";
        // for ($i=0; $i < sizeof($tables); $i++)
        //     $command .= "`$tables[$i]`,";
        // $command = rtrim($command, ",");
        $command = "UPDATE `$table` SET ";
        $arrays = [$columns, $by_columns];
        for ($i=0; $i < sizeof($arrays); $i++) {
            foreach($arrays[$i] as $key => $value) {
                $command .= "`$key`=(?)";
                $values[] = $value;
                if ($i > 0) 
                    $command .= " AND ";
                else
                    $command .= ",";
            }
            if ($i < 1) {
                $command = rtrim($command, ",");
                $command .= " WHERE ";
            } else {
                $command = rtrim($command, " AND ");
            }
        }
        if (is_null($stmt_types))
            $stmt_types = str_repeat("s", sizeof($values));
        return $this->query($command, $stmt_types, $values);
    }

    public function add($table, $columns, $stmt_types=null) {
        $command = "INSERT INTO `$table` (";
        $values = [];
        foreach($columns as $key => $value) {
            $command .= "`$key`,";
            $values[] = $value;
        }
        $command = rtrim($command, ",");
        $command .= ") VALUES (";
        if (is_null($stmt_types))
            $stmt_types = str_repeat("s", sizeof($values));
        $command .= str_repeat('?,', sizeof($values));
        $command = rtrim($command, ",") . ")";
        return $this->query($command, $stmt_types, $values);
    }

    public function delete($table, $by_columns, $stmt_types=null) {
        $command = "DELETE FROM `$table` WHERE ";
        foreach($by_columns as $key => $value) {
            $command .= "`$key`=(?) AND ";
            $values[] = $value;
        }
        $command = rtrim($command, " AND ");
        if (is_null($stmt_types))
            $stmt_types = str_repeat("s", sizeof($values));
        return $this->query($command, $stmt_types, $values);
    }
}