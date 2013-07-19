<?php

namespace CannyDain\ShortyCoreModules\SimpleContentModule;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\ShortyCoreModules\SimpleContentModule\Controllers\ContentAdminController;
use CannyDain\ShortyCoreModules\SimpleContentModule\DataAccess\SimpleContentDataAccess;
use CannyDain\ShortyCoreModules\SimpleContentModule\Installer\SimpleContentInstaller;

class SimpleContentModule extends BaseModule implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    public function getAdminControllerName()
    {
        return ContentAdminController::CONTROLLER_CLASS_NAME;
    }

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller()
    {
        return new SimpleContentInstaller();
    }

    public function initialise()
    {
        $this->datasource()->registerObjects();
    }

    public function enable()
    {

    }

    public function disable()
    {

    }

    /**
     * @return array
     */
    public function getControllerNames()
    {
        return array
        (
            'CannyDain\ShortyCoreModules\SimpleContentModule\Controllers\ContentController',
            'CannyDain\ShortyCoreModules\SimpleContentModule\Controllers\ContentAdminController',
        );
    }

    /**
     * @return ModuleInfo
     */
    public function getInfo()
    {
        $info = new ModuleInfo();

        $info->setAuthor('Danny Cain');
        $info->setAuthorWebsite('www.dannycain.com');
        $info->setName('Simple Content Module');
        $info->setReleaseDate('2013-06-17');
        $info->setVersion('1.0.0');

        return $info;
    }

    /**
     * @return SimpleContentDataAccess
     */
    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new SimpleContentDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }
}