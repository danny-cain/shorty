<?php

namespace CannyDain\ShortyModules\CVLibrary\Models;

use CannyDain\Shorty\Models\ShortyModel;

class CV extends ShortyModel
{
    const OBJECT_TYPE_CV = __CLASS__;

    protected $_id = 0;
    protected $_user = 0;
    protected $_title = '';
    protected $_created = 0;
    protected $_modified = 0;

    protected $_pageTitle = 'Curriculum Vitae';
    protected $_aboutMe = '';
    protected $_hobbiesAndInterests = '';

    public function setAboutMe($aboutMe)
    {
        $this->_aboutMe = $aboutMe;
    }

    public function getAboutMe()
    {
        return $this->_aboutMe;
    }

    public function setHobbiesAndInterests($hobbiesAndInterests)
    {
        $this->_hobbiesAndInterests = $hobbiesAndInterests;
    }

    public function getHobbiesAndInterests()
    {
        return $this->_hobbiesAndInterests;
    }

    public function setPageTitle($pageTitle)
    {
        $this->_pageTitle = $pageTitle;
    }

    public function getPageTitle()
    {
        return $this->_pageTitle;
    }

    public function setCreated($created)
    {
        $this->_created = $created;
    }

    public function getCreated()
    {
        return $this->_created;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setModified($modified)
    {
        $this->_modified = $modified;
    }

    public function getModified()
    {
        return $this->_modified;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }
}