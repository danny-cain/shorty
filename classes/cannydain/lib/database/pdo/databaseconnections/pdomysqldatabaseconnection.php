<?php

namespace CannyDain\Lib\Database\PDO\DatabaseConnections;

use CannyDain\Lib\Database\Exceptions\ConnectionFailedException;
use CannyDain\Lib\Database\Exceptions\NotConnectedException;
use CannyDain\Lib\Database\Exceptions\SQLExecutionException;
use CannyDain\Lib\Database\Interfaces\DatabaseConnection;
use CannyDain\Lib\Database\Interfaces\DatabaseQueryResultInterface;
use CannyDain\Lib\Database\Interfaces\DatabaseStatementResultInterface;
use CannyDain\Lib\Database\Listeners\QueryListener;
use CannyDain\Lib\Database\PDO\Results\PDOQueryResult;
use CannyDain\Lib\Database\PDO\Results\PDOStatementResult;

class PDOMySQLDatabaseConnection implements DatabaseConnection
{
    /**
     * @var \PDO
     */
    protected $_connection;

    /**
     * @var QueryListener[]
     */
    protected $_listeners = array();

    /**
     * @param $sql
     * @param array $params
     * @return DatabaseQueryResultInterface
     */
    public function query($sql, $params = array())
    {
        return new PDOQueryResult($this->_prepareAndExecuteSQL($sql, $params));
    }

    public function registerQueryListener(QueryListener $listener)
    {
        $this->_listeners[] = $listener;
    }

    /**
     * @param $sql
     * @param array $params
     * @return DatabaseStatementResultInterface
     */
    public function statement($sql, $params = array())
    {
        return new PDOStatementResult($this->_prepareAndExecuteSQL($sql, $params));
    }

    public function connect($host, $user, $pass)
    {
        $connectionString = 'mysql:'.$host;

        try
        {
            $this->_connection = new \PDO($connectionString, $user, $pass, array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
        }
        catch(\Exception $e)
        {
            throw new ConnectionFailedException;
        }
    }

    public function selectDatabase($databaseName)
    {
        try
        {
            $sql = 'USE `'.$databaseName.'`';
            $this->_connection->exec($sql);
        }
        catch(\Exception $e)
        {
            throw new SQLExecutionException($sql, array(), $e->getMessage());
        }
    }

    public function isConnected()
    {
        if ($this->_connection == null)
            return false;

        try
        {
            $sql = 'SELECT NOW()';
            $statement = $this->_connection->prepare($sql);
            $statement->execute();
        }
        catch(\Exception $e)
        {
            return false;
        }

        return true;
    }

    public function hasDatabase()
    {
        if ($this->_connection == null)
            return false;

        try
        {
            $sql = 'SELECT DATABASE()';
            $statement = $this->_connection->prepare($sql);
            $statement->execute();

            $row = $statement->fetch(\PDO::FETCH_NUM);
            return array_shift($row) != '';
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    protected function _prepareAndExecuteSQL($sql, $params = array())
    {
        if (!$this->isConnected())
            throw new NotConnectedException;

        try
        {
            $statement = $this->_connection->prepare($sql);
            foreach ($params as $placeholder => $value)
            {
                $type = \PDO::PARAM_STR;
                if (is_null($value))
                    $type = \PDO::PARAM_NULL;
                if (is_int($value))
                    $type = \PDO::PARAM_INT;

                $statement->bindValue($placeholder, $value, $type);
            }

            foreach ($this->_listeners as $listener)
                $listener->queryExecuted($sql, $params);

            $statement->execute();
            return $statement;
        }
        catch(\Exception $e)
        {
            throw new SQLExecutionException($sql, $params, $e->getMessage());
        }
    }
}