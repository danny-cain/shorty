<?php

namespace CannyDain\Lib\GUIDS;

class SimpleGuidManager implements GUIDManagerInterface
{
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