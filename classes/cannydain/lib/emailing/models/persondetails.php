<?php

namespace CannyDain\Lib\Emailing\Models;

class PersonDetails
{
    protected $_name = '';
    protected $_email = '';

    public function __construct($name = '', $email = '')
    {
        $this->_name = $name;
        $this->_email = $email;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }
}