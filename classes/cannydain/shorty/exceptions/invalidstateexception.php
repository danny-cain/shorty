<?php

namespace CannyDain\Shorty\Exceptions;

use CannyDain\Lib\Exceptions\CannyLibException;

class InvalidStateException extends CannyLibException
{
    public function __construct($controller, $message)
    {
        parent::__construct($controller.' is in invalid state: '.$message);
    }

}