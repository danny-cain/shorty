<?php

namespace CannyDain\ShortyCoreModules\ShortyNavigation;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\ShortyCoreModules\ShortyNavigation\Controllers\ShortyNavigationAdminController;
use CannyDain\ShortyCoreModules\ShortyNavigation\DataAccess\ShortyNavigationDataAccess;

class ShortyNavigationModule extends BaseModule implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    public function getAdminControllerName()
    {
        return ShortyNavigationAdminController::CONTROLLER_CLASS_NAME;
    }

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller()
    {
        // TODO: Implement getInstaller() method.
    }

    public function initialise()
    {
        $this->datasource()->registerObjects();
    }

    public function enable()
    {
        // TODO: Implement enable() method.
    }

    public function disable()
    {
        // TODO: Implement disable() method.
    }

    /**
     * @return ModuleInfo
     */
    public function getInfo()
    {
        $info = new ModuleInfo();

        $info->setAuthor('Danny Cain');
        $info->setAuthorWebsite('www.dannycain.com');
        $info->setName('Shorty Navigation');
        $info->setReleaseDate(strtotime('2013-07-03'));
        $info->setVersion("1.0.0");

        return $info;
    }

    /**
     * @return array
     */
    public function getControllerNames()
    {
        return array
        (
            '\\CannyDain\\ShortyCoreModules\\ShortyNavigation\\Controllers\\ShortyNavigationAdminController',
        );
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new ShortyNavigationDataAccess();
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