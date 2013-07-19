<?php

namespace CannyDain\ShortyCoreModules\Diary\Models;

class DiaryEntry
{
    protected $_id = 0;
    protected $_user = 0;

    protected $_text = '';
    protected $_start = 0;
    protected $_end = 0;
    protected $_public = 0;

    public function isPublic() { return $this->_public == 1; }
    public function makePublic() { $this->_public = 1; }
    public function makePrivate() { $this->_public = 0; }

    public function setEnd($end)
    {
        $this->_end = $end;
    }

    public function getEnd()
    {
        return $this->_end;
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

    public function setText($text)
    {
        $this->_text = $text;
    }

    public function getText()
    {
        return $this->_text;
    }

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getUser()
    {
        return $this->_user;
    }
}