<?php

namespace CannyDain\Lib\Database\Listeners;

interface QueryListener
{
    public function queryExecuted($sql, $params);
}