<?php

namespace CannyDain\Lib\Database\Interfaces;

interface DatabaseResultInterface
{
    public function getErrorMessage();
    public function getRowCount();
}