<?php
namespace CannyDain\ShortyCoreModules\payment_invoice;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\ShortyCoreModules\Payment_Invoice\Controllers\InvoiceAdminController;
use CannyDain\ShortyCoreModules\payment_invoice\DataAccess\payment_invoiceDataAccess;

use CannyDain\ShortyCoreModules\payment_invoice\Installer\payment_invoiceInstaller;

class payment_invoiceModule extends BaseModule implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller() { return new payment_invoiceInstaller(); }

    public function getAdminControllerName()
    {
        return InvoiceAdminController::CONTROLLER_CLASS_NAME;
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
        $info->setName("payment_invoice");
        $info->setReleaseDate(1372769861);
        $info->setVersion("1.0.0");

        return $info;
    }

    /**
     * @return array
     */
    public function getControllerNames() { return array(InvoiceAdminController::CONTROLLER_CLASS_NAME); }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new payment_invoiceDataAccess();
            $this->_dependencies->applyDependencies($datasource);
            $datasource->registerObjects();
        }

        return $datasource;
    }

    public function dependenciesConsumed() {}
    public function consumeDependencyInjector(DependencyInjector $dependency) { $this->_dependencies = $dependency; }
}