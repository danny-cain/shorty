<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Models;

class UserStory
{
    const STATUS_TO_BE_SPECCED = 0;
    const STATUS_BEING_SPECCED = 1;
    const STATUS_TO_BE_DEVELOPED = 2;
    const STATUS_BEING_DEVELOPED = 3;
    const STATUS_TO_BE_TESTED = 4;
    const STATUS_BEING_TESTED = 5;
    const STATUS_TO_BE_SIGNED_OFF = 6;
    const STATUS_COMPLETE = 7;

    protected $_id = 0;
    protected $_project = 0;

    protected $_section = '';
    protected $_name = '';
    protected $_priority = 0;
    protected $_estimate = 0;
    protected $_dateCompleted = 0;
    protected $_dateStarted = 0;

    protected $_target = ''; // as a
    protected $_action = ''; // i want to
    protected $_reason = '';  // so that
    protected $_status = self::STATUS_TO_BE_SPECCED;

    public function setDateStarted($dateStarted)
    {
        $this->_dateStarted = $dateStarted;
    }

    public function getDateStarted()
    {
        return $this->_dateStarted;
    }

    public function setDateCompleted($dateCompleted)
    {
        $this->_dateCompleted = $dateCompleted;
    }

    public function getRecommendationWeight()
    {
        return $this->_priority / $this->_estimate;
    }

    public function getDateCompleted()
    {
        return $this->_dateCompleted;
    }

    public function setEstimate($estimate)
    {
        $this->_estimate = $estimate;
    }

    public function getEstimate()
    {
        return $this->_estimate;
    }

    public static function getAllStatusNamesByID()
    {
        $ret = array();

        for ($i = 0; $i <= 7; $i ++)
            $ret[$i] = self::getStatusNameByID($i);

        return $ret;
    }

    public static function getStatusNameByID($statusID)
    {
        switch($statusID)
        {
            case self::STATUS_TO_BE_SPECCED:
                return 'To Be Specified';
            case self::STATUS_BEING_SPECCED:
                return 'Being Specified';
            case self::STATUS_TO_BE_DEVELOPED:
                return 'To Be Developed';
            case self::STATUS_BEING_DEVELOPED:
                return 'Being Developed';
            case self::STATUS_TO_BE_TESTED:
                return 'To Be Tested';
            case self::STATUS_BEING_TESTED:
                return 'Being Tested';
            case self::STATUS_TO_BE_SIGNED_OFF:
                return 'To Be Signed Off';
            case self::STATUS_COMPLETE:
                return 'Complete';
        }
        return 'Unknown';
    }

    public function setPriority($priority)
    {
        $this->_priority = $priority;
    }

    public function getPriority()
    {
        return $this->_priority;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setAction($action)
    {
        $this->_action = $action;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setProject($project)
    {
        $this->_project = $project;
    }

    public function getProject()
    {
        return $this->_project;
    }

    public function setReason($reason)
    {
        $this->_reason = $reason;
    }

    public function getReason()
    {
        return $this->_reason;
    }

    public function setSection($section)
    {
        $this->_section = $section;
    }

    public function getSection()
    {
        return $this->_section;
    }

    public function setTarget($target)
    {
        $this->_target = $target;
    }

    public function getTarget()
    {
        return $this->_target;
    }
}