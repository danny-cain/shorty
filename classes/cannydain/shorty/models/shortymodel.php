<?php

namespace CannyDain\Shorty\Models;

use CannyDain\Lib\DataMapping\DataMapperInterface;
use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\Consumers\GUIDConsumer;

abstract class ShortyModel implements DataMapperConsumer
{
    /**
     * @var DataMapperInterface
     */
    protected $_datamapper;

    /**
     * @return array
     */
    public abstract function validateAndReturnErrors();

    public function save()
    {
        $this->_datamapper->saveObject($this);
    }

    public function consumeDataMapper(DataMapperInterface $datamapper)
    {
        $this->_datamapper = $datamapper;
    }
}