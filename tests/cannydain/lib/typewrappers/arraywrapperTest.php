<?php

namespace Cannydain\Lib\TypeWrappers;

class ArrayWrapperTest extends \PHPUnit_Framework_TestCase
{
    public function testGetValueOnArray_WithInvalidKeys_ReturnsNull()
    {
        $array = array
        (
            'a' => array('b' => 'c')
        );
        $actual = ArrayWrapper::getFieldFromDeepAssociativeArray($array, array('a', 'c'));

        $this->assertNull($actual);
    }

    public function testGetValueOnArray_WithValidKeys_ReturnsValue()
    {
        $array = array
        (
            'a' => array('b' => 'c')
        );
        $expected = 'c';
        $actual = ArrayWrapper::getFieldFromDeepAssociativeArray($array, array('a', 'b'));

        $this->assertEquals($expected, $actual);
    }

    public function testSetValueOnArray_WithInvalidKeys_DoesntChangeArray()
    {
        $expected = array
        (
            'a' => array('b' => 'c')
        );

        $actual = array
        (
            'a' => array('b' => 'c')
        );

        $actual = ArrayWrapper::setFieldInDeepAssociativeArray($actual, 'b', array('a', 'c'));
        $this->assertEquals($expected, $actual);
    }

    public function testSetValueOnArray_WithValidKeys_SetsValue()
    {
        $expected = array
        (
            'a' => array('b' => 'c')
        );

        $actual = array
        (
            'a' => array('b' => 'b')
        );

        $actual = ArrayWrapper::setFieldInDeepAssociativeArray($actual, 'c', array('a', 'b'));
        $this->assertEquals($expected, $actual);
    }
}