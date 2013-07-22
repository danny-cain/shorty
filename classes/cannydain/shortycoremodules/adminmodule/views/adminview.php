<?php

namespace CannyDain\ShortyCoreModules\AdminModule\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;

class AdminView extends HTMLView implements RouterConsumer
{
    /**
     * @var ModuleInterface
     */
    protected $_modules = array();

    /**
     * @var RouterInterface
     */
    protected $_router;

    public function display()
    {
        echo '<h1>Administration</h1>';

        foreach ($this->_modules as $module)
        {
            $this->_displayModule($module);
        }
    }

    protected function _displayModule(ModuleInterface $module)
    {
        $uri = $this->_getLinkForModule($module);
        if ($uri == null)
            return;

        $info = $module->getInfo();
        if ($info == null)
        {
            $name = get_class($module);
            $version = 'unknown';
        }
        else
        {
            $name = $info->getName();
            $version = $info->getVersion();
        }

        echo '<a href="'.$uri.'" class="adminModuleLink">';
            echo '<h2>'.$name.'</h2>';
            echo '<div>';
                echo 'Version: '.$version;
            echo '</div>';
        echo '</a>';
    }

    protected function _getLinkForModule(ModuleInterface $module)
    {
        if ($module->getAdminControllerName() == '')
            return null;

        return $this->_router->getURI(new Route($module->getAdminControllerName()));
    }

    /**
     * @param \CannyDain\Shorty\Modules\Interfaces\ModuleInterface $modules
     */
    public function setModules($modules)
    {
        $this->_modules = $modules;
    }

    /**
     * @return \CannyDain\Shorty\Modules\Interfaces\ModuleInterface
     */
    public function getModules()
    {
        return $this->_modules;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}