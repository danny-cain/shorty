<?php

namespace CannyDain\Lib\Database\Exceptions;

class SQLExecutionException extends DatabaseException
{
    protected $_sql = '';
    protected $_parameters = array();
    protected $_message = '';

    public function __construct($sql, $params, $message)
    {
        $this->_sql = $sql;
        $this->_parameters = $params;
        $this->_message = $message;

        parent::__construct('SQL Exception "'.$message.'" caused by "'.$sql.'"');
    }

    protected function _displayMessage()
    {
        echo '<div><strong>'.$this->_message.'</strong></div>';
        echo '<div>caused by <em>'.$this->_sql.'</em>';
        echo '<div>';
            echo '<pre>'.print_r($this->_parameters, true).'</pre>';
        echo '</div>';
    }


}