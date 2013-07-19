<?php

namespace CannyDain\ShortyCoreModules\SimpleBlog;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\ShortyCoreModules\SimpleBlog\DataAccess\SimpleBlogDatasource;
use CannyDain\ShortyCoreModules\SimpleBlog\Installer\SimpleBlogInstaller;

class SimpleBlogModule extends BaseModule implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    public function dependenciesConsumed()
    {
        $this->datasource()->registerObjects();
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new SimpleBlogDatasource();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller()
    {
        return new SimpleBlogInstaller();
    }

    public function initialise()
    {

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

        $info->setAuthor('Danny Cain');
        $info->setAuthorWebsite('www.dannycain.com');
        $info->setName('Simple Blog');
        $info->setReleaseDate('2013-06-26');
        $info->setVersion('1.0.0');

        return $info;
    }

    /**
     * @return array
     */
    public function getControllerNames()
    {
        return array
        (
            '\\CannyDain\\ShortyCoreModules\\SimpleBlog\\Controllers\\SimpleBlogController',
        );
    }


}