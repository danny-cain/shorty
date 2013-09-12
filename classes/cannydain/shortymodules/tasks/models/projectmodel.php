<?php

namespace CannyDain\ShortyModules\Tasks\Models;

use CannyDain\Shorty\Models\ShortyGUIDModel;

class ProjectModel extends ShortyGUIDModel
{
    const PROJECT_OBJECT_TYPE = __CLASS__;

    protected $_id = 0;
    protected $_name = '';

    /**
     * @return array
     */
    public function validateAndReturnErrors()
    {
        return array();
    }

    protected function _getObjectTypeName()
    {
        return self::PROJECT_OBJECT_TYPE;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }
}