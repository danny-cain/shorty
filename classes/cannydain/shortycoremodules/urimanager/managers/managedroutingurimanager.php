<?php

namespace CannyDain\ShortyCoreModules\URIManager\Managers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Routing\URIManager;
use CannyDain\ShortyCoreModules\URIManager\DataAccess\URIManagerDataAccess;
use CannyDain\ShortyCoreModules\URIManager\Models\URIMappingModel;
use CannyDain\ShortyCoreModules\URIManager\Widgets\URIWidget;

class ManagedRoutingURIManager extends URIManager implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    public function getAssignURIWidgetForRoute(Route $route, $fieldName = 'uri')
    {
        $uri = $this->datasource()->getExactMatchRoute($route);

        if ($uri == null)
        {
            $uri = new URIMappingModel();
            $uri->setController($route->getController());
            $uri->setMethod($route->getMethod());
            $uri->setParams($route->getParams());
            $uri->setUri('');
        }

        $widget = new URIWidget();
        $this->_dependencies->applyDependencies($widget);

        $widget->setFieldname($fieldName);
        $widget->setUri($uri);

        return $widget;
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new URIManagerDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }
}