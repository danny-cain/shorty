<?php
namespace CannyDain\ShortyCoreModules\URIManager;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\ShortyCoreModules\URIManager\Controllers\URIAdminController;
use CannyDain\ShortyCoreModules\URIManager\DataAccess\URIManagerDataAccess;

use CannyDain\ShortyCoreModules\URIManager\Installer\URIManagerInstaller;

class URIManagerModule extends BaseModule implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller() { return new URIManagerInstaller(); }

    public function getAdminControllerName()
    {
        return URIAdminController::CONTROLLER_CLASS_NAME;
    }


    public function initialise()
    {
        $this->datasource()->registerObjects();
    }

    public function enable() {}
    public function disable() {}

    /**
     * @return ModuleInfo
     */
    public function getInfo()
    {
        $info = new ModuleInfo();
        $info->setAuthor("Danny Cain");
        $info->setAuthorWebsite("www.dannycain.com");
        $info->setName("URIManager");
        $info->setReleaseDate(1372937025);
        $info->setVersion("1.0.0");

        return $info;
    }

    /**
     * @return array
     */
    public function getControllerNames() { return array(); }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new URIManagerDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed() {}
    public function consumeDependencyInjector(DependencyInjector $dependency) { $this->_dependencies = $dependency; }
}