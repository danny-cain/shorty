<?php

namespace CannyDain\ShortyModules\CVLibrary\Models;

use CannyDain\Shorty\Models\ShortyModel;

class Qualification extends ShortyModel
{
    const OBJECT_TYPE_QUALIFICATION = __CLASS__;

    protected $_id = 0;
    protected $_course = '';
    protected $_level = '';
    protected $_grade = '';
    protected $_year = '';
    protected $_cv = 0;

    public function setCv($cv)
    {
        $this->_cv = $cv;
    }

    public function getCv()
    {
        return $this->_cv;
    }

    public function setCourse($course)
    {
        $this->_course = $course;
    }

    public function getCourse()
    {
        return $this->_course;
    }

    public function setGrade($grade)
    {
        $this->_grade = $grade;
    }

    public function getGrade()
    {
        return $this->_grade;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setLevel($level)
    {
        $this->_level = $level;
    }

    public function getLevel()
    {
        return $this->_level;
    }

    public function setYear($year)
    {
        $this->_year = $year;
    }

    public function getYear()
    {
        return $this->_year;
    }

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {

    }
}