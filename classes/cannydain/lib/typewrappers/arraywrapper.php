<?php

namespace CannyDain\Lib\TypeWrappers;

class ArrayWrapper
{
    public static function getFieldFromDeepAssociativeArray($array, $keys = array())
    {
        $key = array_shift($keys);
        if (!isset($array[$key]))
            return null;

        if (count($keys) == 0)
        {
            return $array[$key];
        }

        return self::getFieldFromDeepAssociativeArray($array[$key], $keys);
    }

    /**
     * @param $array
     * @param null $newValue
     * @param array $keys an array of keys to determine which value to set
     */
    public static function setFieldInDeepAssociativeArray($array, $newValue = null, $keys = array())
    {
        $key = array_shift($keys);
        if (!isset($array[$key]))
        {
            if (count($keys) > 0)
                $array[$key] = array();
            else
                $array[$key] = null;
        }

        if (count($keys) == 0)
        {
            $array[$key] = $newValue;
            return $array;
        }

        $array[$key] = self::setFieldInDeepAssociativeArray($array[$key], $newValue, $keys);
        return $array;
    }
}