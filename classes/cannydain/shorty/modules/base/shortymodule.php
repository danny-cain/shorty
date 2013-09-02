<?php

namespace CannyDain\Shorty\Modules\Base;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\DataAccess\ShortyDatasource;

abstract class ShortyModule implements ModuleInterface, DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @return ShortyDatasource|null
     */
    public abstract function getDatasource();

    /**
     * Handles registering object's with the datamapper
     * @return void
     */
    public function registerDataObjects()
    {
        $datasource = $this->getDatasource();
        if ($datasource != null)
            $datasource->registerObjects();
    }

    public function consumeDependencies(DependencyInjector $dependencies)
    {
        $this->_dependencies = $dependencies;
    }
}