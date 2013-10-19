<?php

namespace CannyDain\Shorty\Routing;

class RouteManager
{
    /**
     * @var RouteProvider[]
     */
    protected $_providersByType = array();

    public function addProvider(RouteProvider $provider)
    {
        $this->_providersByType[strtolower($provider->getType())] = $provider;
    }

    public function getTypes()
    {
        return array_keys($this->_providersByType);
    }

    /**
     * @param $type
     * @return RouteProvider
     */
    public function getProvider($type)
    {
        if (!isset($this->_providersByType[strtolower($type)]))
            return null;

        return $this->_providersByType[strtolower($type)];
    }
}