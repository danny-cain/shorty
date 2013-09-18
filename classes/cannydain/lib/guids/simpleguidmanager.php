<?php

namespace CannyDain\Lib\GUIDS;

use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;

class SimpleGuidManager implements GUIDManagerInterface
{
    protected $_registriesByType = array();

    /**
     * @param $searchTerm
     * @return ObjectInfoModel[]
     */
    public function searchObjects($searchTerm)
    {
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
}