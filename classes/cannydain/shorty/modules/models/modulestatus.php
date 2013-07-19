<?php

namespace CannyDain\Shorty\Modules\Models;

class ModuleStatus
{
    const STATUS_UNINSTALLED = 0;
    const STATUS_INSTALLED = 1;
    const STATUS_ENABLED = 2;

    protected $_id = 0;
    protected $_moduleName = '';
    protected $_status = self::STATUS_UNINSTALLED;

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setModuleName($moduleName)
    {
        $this->_moduleName = $moduleName;
    }

    public function getModuleName()
    {
        return $this->_moduleName;
    }

    public function setStatus($status)
    {
        $this->_status = $status;
    }

    public function getStatus()
    {
        return $this->_status;
    }
}