<?php

namespace CannyDain\Lib\Database\Listeners;

class FileLoggerQueryListener implements QueryListener
{
    protected $_logFile = '';

    public function __construct($_logFile, $overwrite = true)
    {
        $this->_logFile = $_logFile;
        if (file_exists($_logFile) && $overwrite)
            unlink($_logFile);

        $this->_write("** Log Started ".date('Y-m-d H:i:s')." **");
    }

    protected function _write($data)
    {
        file_put_contents($this->_logFile, $data."\r\n", FILE_APPEND);
    }

    public function queryExecuted($sql, $params)
    {
        ob_start();
            echo $sql."\r\n";
            print_r($params);
        $this->_write(ob_get_clean());
    }

    public function setLogFile($logFile)
    {
        $this->_logFile = $logFile;
    }

    public function getLogFile()
    {
        return $this->_logFile;
    }
}