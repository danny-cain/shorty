<?php

namespace CannyDain\Lib\GUIDS;

use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;

interface GUIDManagerInterface
{
    /**
     * @param $searchTerm
     * @return ObjectInfoModel[]
     */
    public function searchObjects($searchTerm);
    public function registerObjectRegistry(ObjectRegistryProvider $registry);
    public function getGUID($objectType, $objectID);
    public function getType($guid);
    public function getID($guid);
}