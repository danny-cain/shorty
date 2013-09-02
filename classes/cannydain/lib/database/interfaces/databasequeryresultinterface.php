<?php

namespace CannyDain\Lib\Database\Interfaces;

interface DatabaseQueryResultInterface extends DatabaseResultInterface
{
    public function nextRow_AssociativeArray();
    public function nextRow_IndexedArray();
    public function getColumnNames();
}