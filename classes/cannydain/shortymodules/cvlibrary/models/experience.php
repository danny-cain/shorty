<?php

namespace CannyDain\ShortyModules\CVLibrary\Models;

use CannyDain\Shorty\Models\ShortyModel;

class Experience extends ShortyModel
{
    const OBJECT_TYPE_EXPERIENCE = __CLASS__;

    protected $_id = 0;
    protected $_cv = 0;
    protected $_company = '';
    protected $_jobTitle = '';
    protected $_description = '';
    protected $_employmentStart = 0;
    protected $_employmentEnd = 0;

    public function setEmploymentEnd($employmentEnd)
    {
        $this->_employmentEnd = $employmentEnd;
    }

    public function getEmploymentEnd()
    {
        return $this->_employmentEnd;
    }

    public function setEmploymentStart($employmentStart)
    {
        $this->_employmentStart = $employmentStart;
    }

    public function getEmploymentStart()
    {
        return $this->_employmentStart;
    }

    public function setCompany($company)
    {
        $this->_company = $company;
    }

    public function getCompany()
    {
        return $this->_company;
    }

    public function setCv($cv)
    {
        $this->_cv = $cv;
    }

    public function getCv()
    {
        return $this->_cv;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setJobTitle($jobTitle)
    {
        $this->_jobTitle = $jobTitle;
    }

    public function getJobTitle()
    {
        return $this->_jobTitle;
    }

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {

    }
}