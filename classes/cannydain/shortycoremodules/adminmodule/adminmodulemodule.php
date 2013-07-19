<?php
namespace CannyDain\ShortyCoreModules\AdminModule;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\ShortyCoreModules\AdminModule\DataAccess\AdminModuleDataAccess;

use CannyDain\ShortyCoreModules\AdminModule\Installer\AdminModuleInstaller;

class AdminModuleModule extends BaseModule implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller() { return new AdminModuleInstaller(); }

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
        $info->setName("AdminModule");
        $info->setReleaseDate(1372849133);
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
            $datasource = new AdminModuleDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed() {}
    public function consumeDependencyInjector(DependencyInjector $dependency) { $this->_dependencies = $dependency; }
}