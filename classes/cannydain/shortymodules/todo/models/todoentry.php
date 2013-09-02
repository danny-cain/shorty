<?php

namespace CannyDain\ShortyModules\Todo\Models;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Shorty\Consumers\GUIDConsumer;
use CannyDain\Shorty\Models\ShortyGUIDModel;
use CannyDain\Shorty\Models\ShortyModel;

class TodoEntry extends ShortyGUIDModel
{
    const TODO_OBJECT_NAME = __CLASS__;

    const FIELD_TITLE = 'title';
    const FIELD_INFO = 'info';
    const FIELD_CREATED = 'created';
    const FIELD_COMPLETED = 'completed';
    const FIELD_OWNER = 'owner';

    protected $_title = '';
    protected $_info = '';
    protected $_created = 0;
    protected $_completed = 0;
    protected $_owner = 0;

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        $errors = array();

        if ($this->_title == '')
            $errors[self::FIELD_TITLE] = 'You must specify a title';

        return $errors;
    }

    protected function _getObjectTypeName()
    {
        return self::TODO_OBJECT_NAME;
    }

    public function setCompleted($completed)
    {
        $this->_completed = $completed;
    }

    public function getCompleted()
    {
        return $this->_completed;
    }

    public function setCreated($created)
    {
        $this->_created = $created;
    }

    public function getCreated()
    {
        return $this->_created;
    }

    /**
     * @param \CannyDain\Lib\DataMapping\DataMapperInterface $datamapper
     */
    public function setDatamapper($datamapper)
    {
        $this->_datamapper = $datamapper;
    }

    /**
     * @return \CannyDain\Lib\DataMapping\DataMapperInterface
     */
    public function getDatamapper()
    {
        return $this->_datamapper;
    }

    public function setInfo($info)
    {
        $this->_info = $info;
    }

    public function getInfo()
    {
        return $this->_info;
    }

    public function setOwner($owner)
    {
        $this->_owner = $owner;
    }

    public function getOwner()
    {
        return $this->_owner;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }
}