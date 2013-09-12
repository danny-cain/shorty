<?php

namespace CannyDain\ShortyModules\Tasks\Models;

use CannyDain\Shorty\Models\ShortyGUIDModel;

class TaskModel extends ShortyGUIDModel
{
    const TASK_OBJECT_TYPE = __CLASS__;

    const STATUS_TO_BE_SPECCED = 0;
    const STATUS_TO_BE_DEVELOPED = 1;
    const STATUS_TO_BE_TESTED = 2;
    const STATUS_TO_BE_SIGNED_OFF = 3;
    const STATUS_TO_BE_BILLED = 4;
    const STATUS_COMPLETED = 5;

    protected $_id = 0;
    protected $_projectID = 0;

    protected $_priority = 0;
    protected $_estimate = 0;
    protected $_costInPence = 0;
    protected $_title = '';
    protected $_shortDesc = '';
    protected $_longDesc = '';
    protected $_createdDate = 0;
    protected $_completedDate = 0;
    protected $_status = self::STATUS_TO_BE_SPECCED;

    protected function _getObjectTypeName()
    {
        return self::TASK_OBJECT_TYPE;
    }

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }

    public function setCompletedDate($completedDate)
    {
        $this->_completedDate = $completedDate;
    }

    public function getCompletedDate()
    {
        return $this->_completedDate;
    }

    public function setCostInPence($costInPence)
    {
        $this->_costInPence = $costInPence;
    }

    public function getCostInPence()
    {
        return $this->_costInPence;
    }

    public function setCreatedDate($createdDate)
    {
        $this->_createdDate = $createdDate;
    }

    public function getCreatedDate()
    {
        return $this->_createdDate;
    }

    public function setEstimate($estimate)
    {
        $this->_estimate = $estimate;
    }

    public function getEstimate()
    {
        return $this->_estimate;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setLongDesc($longDesc)
    {
        $this->_longDesc = $longDesc;
    }

    public function getLongDesc()
    {
        return $this->_longDesc;
    }

    public function setPriority($priority)
    {
        $this->_priority = $priority;
    }

    public function getPriority()
    {
        return $this->_priority;
    }

    public function setProjectID($projectID)
    {
        $this->_projectID = $projectID;
    }

    public function getProjectID()
    {
        return $this->_projectID;
    }

    public function setShortDesc($shortDesc)
    {
        $this->_shortDesc = $shortDesc;
    }

    public function getShortDesc()
    {
        return $this->_shortDesc;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }
}