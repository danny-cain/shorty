<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\ShortyCoreModules\ProjectManagement\DataAccess\ProjectManagementDataAccess;
use CannyDain\ShortyCoreModules\ProjectManagement\Installer\ProjectManagementInstaller;

class ProjectManagementModule extends BaseModule implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller()
    {
        return new ProjectManagementInstaller();
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
     * @return ModuleInfo
     */
    public function getInfo()
    {
        $info = new ModuleInfo();

        $info->setVersion('1.0.0');
        $info->setAuthor('Danny Cain');
        $info->setAuthorWebsite('www.dannycain.com');
        $info->setName('Project Management Module');
        $info->setReleaseDate('2013-06-27');

        return $info;
    }

    /**
     * @return array
     */
    public function getControllerNames()
    {
        return array
        (
            '\\CannyDain\\ShortyCoreModules\\ProjectManagement\\Controllers\\ProjectManagementController',
        );
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new ProjectManagementDataAccess();
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