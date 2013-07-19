<?php

namespace CannyDain\ShortyCoreModules\UserModule;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\UI\Response\Response;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\ResponseConsumer;
use CannyDain\Shorty\Consumers\UserControlConsumer;
use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use CannyDain\Shorty\UserControl\Interfaces\SessionManager;
use CannyDain\Shorty\UserControl\Interfaces\UserManager;
use CannyDain\Shorty\UserControl\UserControl;
use CannyDain\ShortyCoreModules\UserModule\Controllers\UserAdminController;
use CannyDain\ShortyCoreModules\UserModule\DataAccess\UserModuleDataLayer;
use CannyDain\ShortyCoreModules\UserModule\Installer\UserModuleInstaller;
use CannyDain\ShortyCoreModules\UserModule\Managers\UserAndSessionManager;
use CannyDain\ShortyCoreModules\UserModule\Models\SessionModel;
use CannyDain\ShortyCoreModules\UserModule\Models\UserModel;

class UserModule extends BaseModule implements DependencyConsumer, UserControlConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies = array();

    /**
     * @var UserControl
     */
    protected $_userControl;

    public function getAdminControllerName()
    {
        return UserAdminController::USER_ADMIN_CONTROLLER_NAME;
    }

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller()
    {
        return new UserModuleInstaller();
    }

    public function initialise()
    {
        $this->datasource()->registerObjects();

        $manager = new UserAndSessionManager();
        $this->_dependencies->applyDependencies($manager);

        $manager->initialise();

        $this->_userControl->setSessionManager($manager);
        $this->_userControl->setUserManager($manager);
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
        $inf = new ModuleInfo();

        $inf->setVersion('1.0.0');
        $inf->setReleaseDate('2013-06-18');
        $inf->setName('Users and Sessions Module');
        $inf->setAuthorWebsite('www.dannycain.com');
        $inf->setAuthor('Danny Cain');

        return $inf;
    }

    /**
     * @return array
     */
    public function getControllerNames()
    {
        return array
        (
            '\\CannyDain\\ShortyCoreModules\\UserModule\\Controllers\\UserController',
            '\\CannyDain\\ShortyCoreModules\\UserModule\\Controllers\\UserAdminController',
        );
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new UserModuleDataLayer();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {
        // TODO: Implement dependenciesConsumed() method.
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeUserController(UserControl $dependency)
    {
        $this->_userControl = $dependency;
    }
}