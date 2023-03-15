<?php
class logging {
    public $header = '';
    public $log_filename = "/home/l95243/logs/medicalhub.log";
    public $display_pid = true;

    function __construct($header, $log_filename=null) {
        $this->header = $header;
        if (!is_null($log_filename))
            $this->log_filename = $log_filename;
    }

    function write($message, $header=null) {
        $remote_addr = " "; //localhost
        if (isset($_SERVER['REMOTE_ADDR']))
            $remote_addr = " " . $_SERVER['REMOTE_ADDR'] . " ";
        $date = date("[d/M/y H:i:s]");
        if (is_null($header))
            $header = $this->header;
        if ($this->display_pid)
            $header .= "[" . posix_getpid() . "]";
        $header = "$date$remote_addr$header: ";
        $data = $header . $message . "\n";
        file_put_contents($this->log_filename, $data, FILE_APPEND);
        // $log_file = fopen($this->log_filename, "a");
        // fwrite($log_file, $header . $message . "\n");
        // fclose($log_file);
    }
}
?>
