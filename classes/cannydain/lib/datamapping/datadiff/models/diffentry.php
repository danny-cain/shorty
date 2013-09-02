<?php

namespace CannyDain\Lib\DataMapping\DataDiff\Models;

class DiffEntry
{
    const ACTION_ADD = 'add';
    const ACTION_DROP = 'drop';
    const ACTION_CHANGE = 'change';

    protected $_column = '';
    protected $_action = '';
    protected $_newName = '';
    protected $_newType = '';
    protected $_newSize = 0;

    public function __construct($_action, $_column, $_newName = '', $_newSize = 0, $_newType = '')
    {
        $this->_action = $_action;
        $this->_column = $_column;
        $this->_newName = $_newName;
        $this->_newSize = $_newSize;
        $this->_newType = $_newType;
    }


    public function setAction($action)
    {
        $this->_action = $action;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function setColumn($column)
    {
        $this->_column = $column;
    }

    public function getColumn()
    {
        return $this->_column;
    }

    public function setNewName($newName)
    {
        $this->_newName = $newName;
    }

    public function getNewName()
    {
        return $this->_newName;
    }

    public function setNewSize($newSize)
    {
        $this->_newSize = $newSize;
    }

    public function getNewSize()
    {
        return $this->_newSize;
    }

    public function setNewType($newType)
    {
        $this->_newType = $newType;
    }

    public function getNewType()
    {
        return $this->_newType;
    }
}