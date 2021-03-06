<?php

namespace CannyDain\Lib\GUIDS;

use CannyDain\Lib\GUIDS\Models\ObjectInfoModel;

interface ObjectRegistryProvider
{
    /**
     * @param string $searchTerm
     * @param string $typeLimit
     * @param int $limit
     * @return ObjectInfoModel[]
     */
    public function searchObjects($searchTerm, $typeLimit = null, $limit = 0);

    /**
     * @param $guid
     * @return string
     */
    public function getNameOfObject($guid);

    /**
     * @return array
     */
    public function getKnownTypes();
}