<?php

namespace CannyDain\Lib\Types;

class BitMaskFlagTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFlagsThatArentSet_ReturnsFlagsThatArentSet()
    {
        $value = 10;
        $flags = array(8, 4, 1);
        $expected = array(4, 1);

        $sut = new BitMaskFlag($value);
        $actual = $sut->getFlagsThatArentSet($flags);

        $this->assertEquals($expected, $actual);
    }

    public function testContainsAllOf_ReturnsTrueWhenContainsAllFlags()
    {
        $bits = array(1, 0, 0, 1);
        $flags = array(1, 8);

        $sut = new BitMaskFlag($bits);
        $this->assertTrue($sut->containsAllOf($flags));
    }

    public function testContainsAllOf_ReturnsFalseWhenAFlagIsMissing()
    {
        $bits = array(1, 0, 0, 1);
        $flags = array(1, 2);

        $sut = new BitMaskFlag($bits);
        $this->assertFalse($sut->containsAllOf($flags));
    }

    public function testContainsAnyOf_ReturnsTrueWhenContainsOneFlag()
    {
        $bits = array(1, 0, 0, 1);
        $flags = array(1, 2);

        $sut = new BitMaskFlag($bits);
        $this->assertTrue($sut->containsAnyOf($flags));
    }

    public function testContainsAnyOf_ReturnsFalseWhenContainsNoFlags()
    {
        $bits = array(1, 0, 0, 1);
        $flags = array(4, 2);

        $sut = new BitMaskFlag($bits);
        $this->assertFalse($sut->containsAnyOf($flags));
    }

    public function testConvertValueToBits_ReturnsCorrectBitsForSimpleValue()
    {
        $val = 10;
        /* array is returned starting with the LSB */
        $expected = array(0, 1, 0, 1);
        $actual = BitMaskFlag::ConvertValueToBits($val);

        $this->assertEquals($expected, $actual);
    }

    public function testConvertBitsToValue_ReturnsCorrectValueForSimpleBits()
    {
        $bits = array(1, 1, 1, 1);
        $expected = 15;
        $actual = BitMaskFlag::ConvertBitsToValue($bits);

        $this->assertEquals($expected, $actual);
    }
}