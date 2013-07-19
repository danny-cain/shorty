<?php

namespace CannyDain\Sandbox\DependencyInjection\Dependencies;

class Object1
{
    protected $_name = '';
    protected $_age = 0;

    public function setAge($age)
    {
        $this->_age = $age;
    }

    public function getAge()
    {
        return $this->_age;
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