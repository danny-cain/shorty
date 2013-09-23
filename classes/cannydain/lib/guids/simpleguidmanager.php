<?php

namespace CannyDain\Lib\GUIDS;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;
use CannyDain\Shorty\Consumers\DependencyConsumer;

class SimpleGuidManager implements GUIDManagerInterface, DependencyConsumer
{
    protected $_registriesByType = array();
    protected $_dependenciesInjected = false;
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    protected function _injectDependenciesToProviders()
    {
        if ($this->_dependenciesInjected)
            return;

        $this->_dependenciesInjected = true;
        foreach ($this->_registriesByType as $type => $registries)
        {
            foreach ($registries as $registry)
                $this->_dependencies->applyDependencies($registry);
        }
    }

    public function getName($guid)
    {
        $this->_injectDependenciesToProviders();
        $type = $this->getType($guid);

        if (!isset($this->_registriesByType[$type]))
            return '';

        /**
         * @var ObjectRegistryProvider $registry
         */
        foreach ($this->_registriesByType[$type] as $registry)
        {
            $name = $registry->getNameOfObject($guid);
            if ($name != '')
                return $name;
        }

        return '';
    }

    /**
     * @param $searchTerm
     * @return ObjectInfoModel[]
     */
    public function searchObjects($searchTerm)
    {
        $this->_injectDependenciesToProviders();
        $ret = array();

        foreach ($this->_registriesByType as $type => $registries)
        {
            /**
             * @var ObjectRegistryProvider $registry
             */
            foreach ($registries as $registry)
            {
                $ret = array_merge($ret, $registry->searchObjects($searchTerm));
            }
        }

        return $ret;
    }

    public function registerObjectRegistry(ObjectRegistryProvider $registry)
    {
        foreach ($registry->getKnownTypes() as $type)
        {
            if (!isset($this->_registriesByType[$type]))
                $this->_registriesByType[$type] = array();

            $this->_registriesByType[$type][] = $registry;
        }
    }

    public function getGUID($objectType, $objectID)
    {
        if ($objectType == '')
            $objectType = 'null';

        if ($objectID == '')
            $objectID = 'null';

        return strtr($objectType, array('-' => '_')).'-'.strtr($objectID, array('-' => '_'));
    }

    public function getType($guid)
    {
        $parts = explode('-', $guid);
        return strtr(array_shift($parts), array('_' => '-'));
    }

    public function getID($guid)
    {
        $parts = explode('-', $guid);
        return strtr(array_pop($parts), array('_' => '-'));
    }

    public function consumeDependencies(DependencyInjector $dependencies)
    {
        $this->_dependencies = $dependencies;
    }
}