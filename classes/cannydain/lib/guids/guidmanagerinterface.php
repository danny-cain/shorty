<?php

namespace CannyDain\Lib\GUIDS;

interface GUIDManagerInterface
{
    public function getGUID($objectType, $objectID);
    public function getType($guid);
    public function getID($guid);
}