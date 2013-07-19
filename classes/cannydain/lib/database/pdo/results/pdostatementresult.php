<?php

namespace CannyDain\Lib\Database\PDO\Results;

use CannyDain\Lib\Database\Interfaces\DatabaseStatementResultInterface;

class PDOStatementResult implements DatabaseStatementResultInterface
{
    /**
     * @var \PDOStatement
     */
    protected $_statement;

    public function __construct(\PDOStatement $statement)
    {
        $this->_statement = $statement;
    }

    public function __toString()
    {
        ob_start();
            echo $this->getRowCount().' row(s) affected<br>';
        return ob_get_clean();
    }

    public function getErrorMessage()
    {
        return '';
    }

    public function getRowCount()
    {
        return $this->_statement->rowCount();
    }
}