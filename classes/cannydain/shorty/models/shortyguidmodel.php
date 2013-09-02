<?php

namespace CannyDain\Shorty\Models;

use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Shorty\Consumers\GUIDConsumer;

abstract class ShortyGUIDModel extends ShortyModel implements GUIDConsumer
{
    /**
     * @var GUIDManagerInterface
     */
    protected $_guidmanager;
    protected $_id = 0;

    protected abstract function _getObjectTypeName();

    public function getGUID()
    {
        if ($this->_id == 0)
            return null;

        $guid = $this->_guidmanager->getGUID($this->_getObjectTypeName(), $this->_id);
        return $guid;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function consumeGUIDManager(GUIDManagerInterface $guidManager)
    {
        $this->_guidmanager = $guidManager;
    }
}
