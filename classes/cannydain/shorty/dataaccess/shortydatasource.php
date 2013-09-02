<?php

namespace CannyDain\Shorty\DataAccess;

use CannyDain\Lib\DataMapping\DataMapperInterface;
use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\Consumers\DependencyConsumer;

abstract class ShortyDatasource implements DataMapperConsumer, DependencyConsumer
{
    /**
     * @var DataMapperInterface
     */
    protected $_datamapper;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    public abstract function registerObjects();

    public function consumeDataMapper(DataMapperInterface $datamapper)
    {
        $this->_datamapper = $datamapper;
    }

    public function consumeDependencies(DependencyInjector $dependencies)
    {
        $this->_dependencies = $dependencies;
    }
}