<?php

namespace CannyDain\ShortyModules\CVLibrary\Models;

use CannyDain\Shorty\Models\ShortyModel;

class CVCategory extends ShortyModel
{
    const OBJECT_TYPE_CV_CATEGORY = __CLASS__;

    protected $_id = 0;
    protected $_name = '';

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

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }
}