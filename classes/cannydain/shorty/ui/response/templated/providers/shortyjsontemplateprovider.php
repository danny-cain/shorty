<?php

namespace CannyDain\Shorty\UI\Response\Templated\Providers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\UI\Response\TemplatedDocuments\TemplateProviders\JSONTemplateProvider;
use CannyDain\Shorty\Consumers\DependencyConsumer;

class ShortyJSONTemplateProvider extends JSONTemplateProvider implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    protected function _elementFactory($classname, $constructorParams = array())
    {
        $ret = parent::_elementFactory($classname, $constructorParams);
        $this->_dependencies->applyDependencies($ret);

        return $ret;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }
}