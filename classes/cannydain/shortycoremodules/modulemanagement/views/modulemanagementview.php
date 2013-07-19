<?php

namespace CannyDain\ShortyCoreModules\ModuleManagement\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Modules\Models\ModuleStatus;

class ModuleManagementView extends HTMLView
{
    protected $_enableURITemplate = '';
    protected $_disableURITemplate = '';
    protected $_installURITemplate = '';
    protected $_scanURI = '';

    /**
     * @var ModuleStatus[]
     */
    protected $_modules;

    public function display()
    {
        echo '<h1>Module Management</h1>';

        foreach ($this->_modules as $module)
            $this->_displayModule($module);

        echo '<form method="post" action="'.$this->_scanURI.'" onsubmit="return confirm(\'are you sure you wish to do another scan? this may take a while\');">';
            echo '<input type="submit" value="Scan" class="itemActionButton" />';
        echo '</form>';
    }

    protected function _displayModule(ModuleStatus $module)
    {
        echo '<div>';
            echo '<div style="display: inline-block; width: 100px; margin-right: 10px;">';
                echo $this->_getActionButtonsForModule($module);
            echo '</div>';

            echo '<div style="display: inline-block;">';
                echo $module->getModuleName();
            echo '</div>';
        echo '</div>';
    }

    protected function _getActionButtonsForModule(ModuleStatus $module)
    {
        $uriTemplate = '';
        $text = '';

        switch($module->getStatus())
        {
            case ModuleStatus::STATUS_ENABLED:
                $uriTemplate = $this->_disableURITemplate;
                $text = 'Disable';
                break;
            case ModuleStatus::STATUS_INSTALLED:
                $uriTemplate = $this->_enableURITemplate;
                $text = 'Enable';
                break;
            case ModuleStatus::STATUS_UNINSTALLED:
                $uriTemplate = $this->_installURITemplate;
                $text = 'Install';
                break;
        }
        $uri = strtr($uriTemplate, array('#id#' => $module->getId()));

        return <<<HTML
<form method="post" action="{$uri}" onsubmit="return confirm('Are you sure?')">
    <input type="submit" class="itemActionButton" value="{$text}" />
</form>
HTML;

    }

    public function setScanURI($scaURI)
    {
        $this->_scanURI = $scaURI;
    }

    public function getScanURI()
    {
        return $this->_scanURI;
    }

    public function setDisableURITemplate($disableURITemplate)
    {
        $this->_disableURITemplate = $disableURITemplate;
    }

    public function getDisableURITemplate()
    {
        return $this->_disableURITemplate;
    }

    public function setEnableURITemplate($enableURITemplate)
    {
        $this->_enableURITemplate = $enableURITemplate;
    }

    public function getEnableURITemplate()
    {
        return $this->_enableURITemplate;
    }

    public function setInstallURITemplate($installURITemplate)
    {
        $this->_installURITemplate = $installURITemplate;
    }

    public function getInstallURITemplate()
    {
        return $this->_installURITemplate;
    }

    public function setModules($modules)
    {
        $this->_modules = $modules;
    }

    public function getModules()
    {
        return $this->_modules;
    }
}