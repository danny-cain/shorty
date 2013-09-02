<?php

namespace CannyDain\Lib\Database\Interfaces;

use CannyDain\Lib\Database\Listeners\QueryListener;

interface DatabaseConnection
{
    /**
     * @param $sql
     * @param array $params
     * @return DatabaseQueryResultInterface
     */
    public function query($sql, $params = array());

    public function registerQueryListener(QueryListener $listener);

    /**
     * @param $sql
     * @param array $params
     * @return DatabaseStatementResultInterface
     */
    public function statement($sql, $params = array());


    /**
     * @param $host
     * @param $user
     * @param $pass
     * @return void
     */
    public function connect($host, $user, $pass);

    /**
     * @param $databaseName
     * @return void
     */
    public function selectDatabase($databaseName);

    /**
     * @return bool
     */
    public function isConnected();

    /**
     * @return bool
     */
    public function hasDatabase();
}