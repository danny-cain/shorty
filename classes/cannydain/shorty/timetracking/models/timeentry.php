<?php

namespace CannyDain\Shorty\TimeTracking\Models;

class TimeEntry
{
    protected $_id = 0;
    protected $_guid = '';
    protected $_start = 0;
    protected $_end = 0;
    protected $_comment = '';
    protected $_user = 0;

    public function getTimeInSeconds()
    {
        return $this->_end - $this->_start;
    }

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function setComment($comment)
    {
        $this->_comment = $comment;
    }

    public function getComment()
    {
        return $this->_comment;
    }

    public function setEnd($end)
    {
        $this->_end = $end;
    }

    public function getEnd()
    {
        return $this->_end;
    }

    public function setGuid($guid)
    {
        $this->_guid = $guid;
    }

    public function getGuid()
    {
        return $this->_guid;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setStart($start)
    {
        $this->_start = $start;
    }

    public function getStart()
    {
        return $this->_start;
    }
}
