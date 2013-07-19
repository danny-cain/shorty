<?php

namespace CannyDain\ShortyCoreModules\ModuleManagement\Controllers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Execution\Interfaces\ControllerInterface;
use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\ViewFactory;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\Consumers\ViewFactoryConsumer;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\Shorty\UserControl\UserControl;
use CannyDain\ShortyCoreModules\ModuleManagement\Views\ModuleManagementView;
use Exception;

class ModuleManagementController implements ControllerInterface, RequestConsumer, DependencyConsumer, ModuleConsumer, UserControlConsumer, RouterConsumer, ViewFactoryConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var UserControl
     */
    protected $_userControl;

    /**
     * @var ViewFactory
     */
    protected $_viewFactory;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @var ModuleManager
     */
    protected $_moduleManager;

    public function Index()
    {
        /**
         * @var ModuleManagementView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\ShortyCoreModules\\ModuleManagement\\Views\\ModuleManagementView');
        $this->_dependencies->applyDependencies($view);

        $view->setDisableURITemplate($this->_router->getURI(new Route(__CLASS__, 'DisableModule', array('#id#'))));
        $view->setEnableURITemplate($this->_router->getURI(new Route(__CLASS__, 'EnableModule', array('#id#'))));
        $view->setInstallURITemplate($this->_router->getURI(new Route(__CLASS__, 'InstallModule', array('#id#'))));
        $view->setScanURI($this->_router->getURI(new Route(__CLASS__, 'ScanForNewModules')));
        $view->setModules($this->_moduleManager->getAllModuleStatuses());

        return $view;
    }

    protected function _ensureUserIsAdmin()
    {
        $userID = $this->_userControl->getCurrentUserID();
        if (!$this->_userControl->isAdministrator($userID))
            throw new Exception("Unauthorised");
    }

    protected function _getPRGRedirectView($url)
    {
        $view = new RedirectView();
        $this->_dependencies->applyDependencies($view);
        $view->setResponseCode(RedirectView::RESPONSE_CODE_TEMPORARY_REDIRECT);
        $view->setUri($url);

        return $view;
    }

    public function ScanForNewModules()
    {
        $this->_ensureUserIsAdmin();
        if ($this->_request->isPost())
            $this->_moduleManager->scanForModules();

        return $this->_getPRGRedirectView($this->_router->getURI(new Route(__CLASS__)));
    }

    public function InstallModule($moduleID)
    {
        $this->_ensureUserIsAdmin();
        if ($this->_request->isPost())
            $this->_moduleManager->installModule($moduleID);

        return $this->_getPRGRedirectView($this->_router->getURI(new Route(__CLASS__)));
    }

    public function EnableModule($moduleID)
    {
        $this->_ensureUserIsAdmin();
        if ($this->_request->isPost())
            $this->_moduleManager->enableModule($moduleID);

        return $this->_getPRGRedirectView($this->_router->getURI(new Route(__CLASS__)));
    }

    public function DisableModule($moduleID)
    {
        $this->_ensureUserIsAdmin();
        if ($this->_request->isPost())
            $this->_moduleManager->disableModule($moduleID);

        return $this->_getPRGRedirectView($this->_router->getURI(new Route(__CLASS__)));
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeModuleManager(ModuleManager $dependency)
    {
        $this->_moduleManager = $dependency;
    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeUserController(UserControl $dependency)
    {
        $this->_userControl = $dependency;
    }

    public function consumeViewFactory(ViewFactory $dependency)
    {
        $this->_viewFactory = $dependency;
    }

    /**
     * @return bool
     */
    public function __isAdministratorOnly()
    {
        return true;
    }
}