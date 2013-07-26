<?php

namespace CannyDain\Shorty\Comments\Models;

class CommentsSettingsEntry
{
    const SETTING_CAN_POST_COMMENTS = 'canPostComments';
    const SETTING_DISPLAY_COMMENTS = 'displayComments';

    protected $_id = 0;
    protected $_objectGUID = '';
    protected $_setting = '';
    protected $_value = '';

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setObjectGUID($objectGUID)
    {
        $this->_objectGUID = $objectGUID;
    }

    public function getObjectGUID()
    {
        return $this->_objectGUID;
    }

    public function setSetting($setting)
    {
        $this->_setting = $setting;
    }

    public function getSetting()
    {
        return $this->_setting;
    }

    public function setValue($value)
    {
        $this->_value = $value;
    }

    public function getValue()
    {
        return $this->_value;
    }
}