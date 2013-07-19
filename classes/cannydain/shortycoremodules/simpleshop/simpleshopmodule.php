<?php
namespace CannyDain\ShortyCoreModules\SimpleShop;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\ShortyCoreModules\SimpleShop\Controllers\SimpleShopAdminController;
use CannyDain\ShortyCoreModules\SimpleShop\DataAccess\SimpleShopDataAccess;

use CannyDain\ShortyCoreModules\SimpleShop\Installer\SimpleShopInstaller;

class SimpleShopModule extends BaseModule implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller() { return new SimpleShopInstaller(); }

    public function getAdminControllerName()
    {
        return SimpleShopAdminController::CONTROLLER_CLASS_NAME;
    }

    public function initialise()
    {

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
        $info->setName("SimpleShop");
        $info->setReleaseDate(1372767101);
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
            $datasource = new SimpleShopDataAccess();
            $this->_dependencies->applyDependencies($datasource);
            $this->datasource()->registerObjects();
        }

        return $datasource;
    }

    public function dependenciesConsumed() {}
    public function consumeDependencyInjector(DependencyInjector $dependency) { $this->_dependencies = $dependency; }
}