<?php
namespace CannyDain\ShortyCoreModules\TemplateManager;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\InstanceManagerConsumer;
use CannyDain\Shorty\InstanceManager\InstanceManager;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\ShortyCoreModules\TemplateManager\Controllers\TemplateEditorController;
use CannyDain\ShortyCoreModules\TemplateManager\DataAccess\TemplateManagerDataAccess;

use CannyDain\ShortyCoreModules\TemplateManager\Installer\TemplateManagerInstaller;

class TemplateManagerModule extends BaseModule implements DependencyConsumer, InstanceManagerConsumer
{
    const ELEMENT_TYPE_NAME = '\CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement';
    const CONTAINER_ELEMENT_INTERFACE = 'CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplateDocumentElementContainerInterface';

    /**
     * @var InstanceManager
     */
    protected $_instanceManager;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller() { return new TemplateManagerInstaller(); }

    public function getAdminControllerName()
    {
        return TemplateEditorController::CONTROLLER_CLASS_NAME;
    }

    public function initialise()
    {
        $this->datasource()->registerObjects();
        $this->_instanceManager->ensureTypeIsRegistered(self::ELEMENT_TYPE_NAME, 'Template Element');
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
        $info->setName("TemplateManager");
        $info->setReleaseDate(1373534162);
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
            $datasource = new TemplateManagerDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed() {}
    public function consumeDependencyInjector(DependencyInjector $dependency) { $this->_dependencies = $dependency; }

    public function consumeInstanceManager(InstanceManager $dependency)
    {
        $this->_instanceManager = $dependency;
    }
}