<?php

namespace CannyDain\ShortyModules\Users;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\Users\Datasource\UsersDatasource;

class UsersModule extends ShortyModule
{
    const USERS_MODULE_CLASS = __CLASS__;

    /**
     * @var Route
     */
    protected $_loginRedirectRoute = null;

    /**
     * @param Route $loginRoute
     */
    public function __construct($loginRoute = null)
    {
        $this->_loginRedirectRoute = $loginRoute;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $loginRedirectRoute
     */
    public function setLoginRedirectRoute($loginRedirectRoute)
    {
        $this->_loginRedirectRoute = $loginRedirectRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getLoginRedirectRoute()
    {
        return $this->_loginRedirectRoute;
    }

    /**
     * @return UsersDatasource
     */
    public function getDatasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new UsersDatasource();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    /**
     * Allows the module to perform any initialisation actions (i.e. loading in session etc)
     * @return void
     */
    public function initialise()
    {

    }

    /**
     * @return ModuleInfoModel
     */
    public function getInfo()
    {
        return new ModuleInfoModel('Users Module', 'Danny Cain', '0.1');
    }
}