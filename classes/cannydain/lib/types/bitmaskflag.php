<?php

namespace CannyDain\Lib\Types;

class BitMaskFlag
{
    protected $_value = 0;
    protected $_bits = array();

    public function __construct($valueOrBits = array())
    {
        if (is_array($valueOrBits))
            $this->setBits($valueOrBits);
        else
            $this->setValue($valueOrBits);
    }

    /**
     * Returns an array of flags that AREN'T set on the current object
     * @param array $flags
     * @param array $flags
     * @return array
     */
    public function getFlagsThatArentSet($flags = array())
    {
        $ret = array();

        foreach ($flags as $flag)
        {
            $index = $this->_getIndexByBitValue($flag);
            if (!isset($this->_bits[$index]) || $this->_bits[$index] == 0)
                $ret[] = $flag;
        }

        return $ret;
    }

    public function containsAnyOf($flags = array())
    {
        foreach ($flags as $flag)
        {
            $flag = $this->_getIndexByBitValue($flag);
            if (!isset($this->_bits[$flag]))
                continue;

            if ($this->_bits[$flag] == 1)
                return true;
        }

        return false;
    }

    public function containsAllOf($flags = array())
    {
        foreach ($flags as $flag)
        {
            $flag = $this->_getIndexByBitValue($flag);
            if (!isset($this->_bits[$flag]))
                return false;

            if ($this->_bits[$flag] == 0)
                return false;
        }

        return true;
    }

    protected function _getIndexByBitValue($bitValue)
    {
        $index = 0;
        $weight = 1;
        while (($weight * 2) - 1 < $bitValue)
        {
            $index ++;
            $weight = $weight * 2;
        }

        return $index;
    }

    public function setBits($bits)
    {
        $this->_bits = $bits;
        $this->_value = self::ConvertBitsToValue($bits);
    }

    public function getBits()
    {
        return $this->_bits;
    }

    public function setValue($value)
    {
        $this->_value = $value;
        $this->_bits = self::ConvertValueToBits($value);
    }

    public function getValue()
    {
        return $this->_value;
    }

    public static function ConvertValueToBits($value)
    {
        $bits = array();
        $weight = 1;

        while (($weight * 2) - 1 < $value)
            $weight = $weight * 2;

        while ($weight > 0.5)
        {
            if ($value >= $weight)
            {
                $value = $value - $weight;
                $bits[] = 1;
            }
            else
                $bits[] = 0;

            $weight = $weight / 2;
        }

        return array_reverse($bits);
    }

    public static function ConvertBitsToValue($bits)
    {
        $value = 0;
        $weight = 1;
        foreach ($bits as $bit)
        {
            if ($bit == 1)
                $value += $weight;

            $weight = $weight * 2;
        }

        return $value;
    }
}