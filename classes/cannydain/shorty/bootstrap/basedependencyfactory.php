<?php

namespace CannyDain\Shorty\Bootstrap;

use CannyDain\Lib\CommentsManager\NullCommentsManager;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\Database\PDO\DatabaseConnections\PDOMySQLDatabaseConnection;
use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\DependencyInjection\Interfaces\DependencyFactoryInterface;
use CannyDain\Lib\Emailing\NullEmailer;
use CannyDain\Lib\Emailing\SMTPEmailer;
use CannyDain\Lib\Execution\DefaultControllerFactory;
use CannyDain\Lib\Execution\Interfaces\ControllerFactoryInterface;
use CannyDain\Lib\GUIDS\SimpleGuidManager;
use CannyDain\Lib\ObjectPermissions\NullObjectPermissionsManager;
use CannyDain\Lib\ObjectPermissions\ObjectPermissionsManagerInterface;
use CannyDain\Lib\Routing\Routers\DirectMappedRouter;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\ECommerce\Basket\BasketHelper;
use CannyDain\Shorty\ECommerce\Products\ProductManager;
use CannyDain\Shorty\Events\EventManager;
use CannyDain\Shorty\FileManager\FileManager;
use CannyDain\Shorty\FileManager\FileManagerInterface;
use CannyDain\Shorty\Finance\InvoiceManager;
use CannyDain\Shorty\Finance\PaymentManager;
use CannyDain\Shorty\Geo\AddressManager;
use CannyDain\Shorty\Helpers\AccessControl\NullAccessControlHelper;
use CannyDain\Shorty\Helpers\Forms\FormHelper;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Helpers\UserHelper;
use CannyDain\Shorty\Helpers\ViewHelper\ViewHelper;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\Shorty\RouteAccessControl\DefaultRouteAccessControl;
use CannyDain\Shorty\Routing\RouteManager;

class BaseDependencyFactory implements DependencyFactoryInterface
{
    const CONSUMER_DATABASE = '\\CannyDain\\Shorty\\Consumers\\DatabaseConsumer';
    const CONSUMER_DEPENDENCIES = '\\CannyDain\\Shorty\\Consumers\\DependencyConsumer';
    const CONSUMER_ROUTER = '\\CannyDain\\Shorty\\Consumers\\RouterConsumer';
    const CONSUMER_REQUEST = '\\CannyDain\\Shorty\\Consumers\\RequestConsumer';
    const CONSUMER_DATA_MAPPER = '\\CannyDain\\Shorty\\Consumers\\DataMapperConsumer';
    const CONSUMER_EVENTS = '\\CannyDain\\Shorty\\Consumers\\EventConsumer';
    const CONSUMER_EMAIL = '\\CannyDain\\Shorty\\Consumers\\EmailConsumer';
    const CONSUMER_COMMENTS = '\\CannyDain\\Shorty\\Consumers\\CommentsConsumer';
    const CONSUMER_MODULES = '\\CannyDain\\Shorty\\Consumers\\ModuleConsumer';
    const CONSUMER_GUIDS = '\\CannyDain\\Shorty\\Consumers\\GUIDConsumer';
    const CONSUMER_FORM_HELPER = '\\CannyDain\\Shorty\\Consumers\\FormHelperConsumer';
    const CONSUMER_SESSION = '\\CannyDain\\Shorty\\Consumers\\SessionConsumer';
    const CONSUMER_USERS = '\\CannyDain\\Shorty\\Consumers\\UserConsumer';
    const CONSUMER_ACCESS_CONTROL = '\\CannyDain\\Shorty\\Consumers\\AccessControlConsumer';
    const CONSUMER_VIEW_HELPER = '\\CannyDain\\Shorty\\Consumers\\ViewHelperConsumer';
    const CONSUMER_ROUTE_ACCESS_CONTROL = '\\CannyDain\\Shorty\\Consumers\\RouteAccessControlConsumer';
    const CONSUMER_ADDRESS_MANAGER = '\\CannyDain\\Shorty\\Consumers\\AddressManagerConsumer';
    const CONSUMER_PAYMENT_MANAGER = '\\CannyDain\\Shorty\\Consumers\\PaymentManagerConsumer';
    const CONSUMER_INVOICE_MANAGER = '\\CannyDain\\Shorty\\Consumers\\InvoiceManagerConsumer';
    const CONSUMER_BASKET_HELPER = '\\CannyDain\\Shorty\\Consumers\\BasketHelperConsumer';
    const CONSUMER_PRODUCT_MANAGER = '\\CannyDain\\Shorty\\Consumers\\ProductManagerConsumer';
    const CONSUMER_OBJECT_PERMISSIONS = '\\CannyDain\\Shorty\\Consumers\\ObjectPermissionsConsumer';
    const CONSUMER_CONTROLLER_FACTORY = '\\CannyDain\\Shorty\\Consumers\\ControllerFactoryConsumer';
    const CONSUMER_FILE_MANAGER = '\\CannyDain\\Shorty\\Consumers\\FileManagerConsumer';
    const CONSUMER_ROUTE_MANAGER = '\\CannyDain\\Shorty\\Consumers\\RouteManagerConsumer';

    /**
     * @var DependencyInjector
     */
    protected $_dependencyInjector;

    /**
     * @var ShortyConfiguration
     */
    protected $_config;

    protected $_cachedDependenciesByInterface = array();

    public function getInterfaces()
    {
        return array
        (
            self::CONSUMER_DEPENDENCIES,
            self::CONSUMER_DATABASE,
            self::CONSUMER_ROUTER,
            self::CONSUMER_REQUEST,
            self::CONSUMER_DATA_MAPPER,
            self::CONSUMER_EVENTS,
            self::CONSUMER_EMAIL,
            self::CONSUMER_COMMENTS,
            self::CONSUMER_MODULES,
            self::CONSUMER_GUIDS,
            self::CONSUMER_FORM_HELPER,
            self::CONSUMER_SESSION,
            self::CONSUMER_USERS,
            self::CONSUMER_ACCESS_CONTROL,
            self::CONSUMER_VIEW_HELPER,
            self::CONSUMER_ROUTE_ACCESS_CONTROL,
            self::CONSUMER_PAYMENT_MANAGER,
            self::CONSUMER_ADDRESS_MANAGER,
            self::CONSUMER_INVOICE_MANAGER,
            self::CONSUMER_BASKET_HELPER,
            self::CONSUMER_PRODUCT_MANAGER,
            self::CONSUMER_OBJECT_PERMISSIONS,
            self::CONSUMER_CONTROLLER_FACTORY,
            self::CONSUMER_FILE_MANAGER,
            self::CONSUMER_ROUTE_MANAGER,
        );
    }

    /**
     * @return RouteManager
     */
    protected function _factory_routeManager()
    {
        $manager = new RouteManager();

        return $manager;
    }

    /**
     * @return FileManagerInterface
     */
    protected function _factory_fileManager()
    {
        $fsRoot = $this->_config->getValue(ShortyConfiguration::KEY_PUBLIC_ROOT);
        $webRoot = $this->_config->getValue(ShortyConfiguration::KEY_PUBLIC_ROOT);
        $privateRoot = $this->_config->getValue(ShortyConfiguration::KEY_PRIVATE_DATA_ROOT);

        $fileManager = new FileManager($webRoot, $fsRoot, $privateRoot);

        return $fileManager;
    }

    /**
     * @return ControllerFactoryInterface
     */
    protected function _factory_controllerFactory()
    {
        return new DefaultControllerFactory();
    }

    /**
     * @return ObjectPermissionsManagerInterface
     */
    protected function _factory_objectPermissions()
    {
        return new NullObjectPermissionsManager();
    }

    protected function _factory_productManager()
    {
        $manager = new ProductManager();

        return $manager;
    }

    protected function _factory_basketHelper()
    {
        return new BasketHelper();
    }

    protected function _factory_invoiceManager()
    {
        return new InvoiceManager();
    }

    protected function _factory_paymentManager()
    {
        $manager = new PaymentManager();

        return $manager;
    }

    protected function _factory_addressManager()
    {
        return new AddressManager();
    }

    protected function _factory_routeAccessControl()
    {
        return new DefaultRouteAccessControl(true);
    }

    protected function _factory_viewHelper()
    {
        return new ViewHelper();
    }

    protected function _factory_accessControl()
    {
        return new NullAccessControlHelper();
    }

    protected function _factory_userHelper()
    {
        return new UserHelper();
    }

    protected function _factory_session()
    {
        return new SessionHelper();
    }

    protected function _factory_formHelper()
    {
        return new FormHelper();
    }

    protected function _factory_guidManager()
    {
        return new SimpleGuidManager();
    }

    protected function _initialiseModules(ModuleManager $moduleManager)
    {

    }

    protected function _factory_modules()
    {
        $manager = new ModuleManager();

        $this->_initialiseModules($manager);

        return $manager;
    }

    protected function _factory_comments()
    {
        return new NullCommentsManager();
    }

    protected function _factory_emailer()
    {
        $host = $this->_config->getValue(ShortyConfiguration::KEY_SMTP_HOST);
        $user = $this->_config->getValue(ShortyConfiguration::KEY_SMTP_USER);
        $pass = $this->_config->getValue(ShortyConfiguration::KEY_SMTP_PASS);

        if ($host == '')
            return new NullEmailer();

        return new SMTPEmailer($host, $user, $pass);
    }

    protected function _factory_eventManager()
    {
        return new EventManager();
    }

    protected function _factory_dataMapper()
    {
        return new DataMapper($this->createInstance(self::CONSUMER_DATABASE), $this->createInstance(self::CONSUMER_DEPENDENCIES));
    }

    protected function _factory_request()
    {
        $request = new Request();
        $request->loadFromHTTPRequest('r');

        return $request;
    }

    protected function _factory_router()
    {
        return new DirectMappedRouter();
    }

    protected function _factory_database()
    {
        $host = $this->_config->getValue(ShortyConfiguration::KEY_DATABASE_HOST);
        $name = $this->_config->getValue(ShortyConfiguration::KEY_DATABASE_NAME);
        $user = $this->_config->getValue(ShortyConfiguration::KEY_DATABASE_USER);
        $pass = $this->_config->getValue(ShortyConfiguration::KEY_DATABASE_PASS);

        $database = new PDOMySQLDatabaseConnection();
        $database->connect($host, $user, $pass);
        $database->selectDatabase($name);

        return $database;
    }

    /**
     * @param $consumerInterface
     * @return mixed
     */
    public function createInstance($consumerInterface)
    {
        if (isset($this->_cachedDependenciesByInterface[$consumerInterface]))
            return $this->_cachedDependenciesByInterface[$consumerInterface];

        // note: if a dependency needs to create a new instance every time then just return the
        //  instance from within the switch (don't forget to apply dependencies)
        switch($consumerInterface)
        {
            case self::CONSUMER_DEPENDENCIES:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_dependencyInjector;
                break;
            case self::CONSUMER_DATABASE:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_database();
                break;
            case self::CONSUMER_ROUTER:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_router();
                break;
            case self::CONSUMER_REQUEST:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_request();
                break;
            case self::CONSUMER_DATA_MAPPER:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_dataMapper();
                break;
            case self::CONSUMER_EVENTS:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_eventManager();
                break;
            case self::CONSUMER_EMAIL:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_emailer();
                break;
            case self::CONSUMER_COMMENTS:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_comments();
                break;
            case self::CONSUMER_MODULES:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_modules();
                break;
            case self::CONSUMER_GUIDS:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_guidManager();
                break;
            case self::CONSUMER_FORM_HELPER:
                $helper = $this->_factory_formHelper();
                $this->_dependencyInjector->applyDependencies($helper);
                return $helper;
            case self::CONSUMER_SESSION:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_session();
                break;
            case self::CONSUMER_USERS:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_userHelper();
                break;
            case self::CONSUMER_ACCESS_CONTROL:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_accessControl();
                break;
            case self::CONSUMER_VIEW_HELPER:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_viewHelper();
                break;
            case self::CONSUMER_ROUTE_ACCESS_CONTROL:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_routeAccessControl();
                break;
            case self::CONSUMER_ADDRESS_MANAGER:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_addressManager();
                break;
            case self::CONSUMER_PAYMENT_MANAGER:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_paymentManager();
                break;
            case self::CONSUMER_INVOICE_MANAGER:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_invoiceManager();
                break;
            case self::CONSUMER_BASKET_HELPER:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_basketHelper();
                break;
            case self::CONSUMER_PRODUCT_MANAGER:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_productManager();
                break;
            case self::CONSUMER_OBJECT_PERMISSIONS:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_objectPermissions();
                break;
            case self::CONSUMER_CONTROLLER_FACTORY:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_controllerFactory();
                break;
            case self::CONSUMER_FILE_MANAGER:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_fileManager();
                break;
            case self::CONSUMER_ROUTE_MANAGER:
                $this->_cachedDependenciesByInterface[$consumerInterface] = $this->_factory_routeManager();
                break;
            default:
                $object = $this->_createOtherInstance($consumerInterface);
                if ($object == null)
                    return null;

                $this->_dependencyInjector->applyDependencies($object);
                return $object;
                break;
        }

        if (isset($this->_cachedDependenciesByInterface[$consumerInterface]))
        {
            $this->_dependencyInjector->applyDependencies($this->_cachedDependenciesByInterface[$consumerInterface]);
            return $this->_cachedDependenciesByInterface[$consumerInterface];
        }

        return null;
    }

    /**
     * @param $interface
     * Create instance and return it, place in dependency cache if necessary
     * @return object
     */
    protected function _createOtherInstance($interface)
    {
        return null;
    }

    /**
     * @param \CannyDain\Lib\DependencyInjection\DependencyInjector $dependencyInjector
     */
    public function setDependencyInjector($dependencyInjector)
    {
        $this->_dependencyInjector = $dependencyInjector;
    }

    /**
     * @return \CannyDain\Lib\DependencyInjection\DependencyInjector
     */
    public function getDependencyInjector()
    {
        return $this->_dependencyInjector;
    }

    /**
     * @param \CannyDain\Shorty\Config\ShortyConfiguration $config
     */
    public function setConfig($config)
    {
        $this->_config = $config;
    }

    /**
     * @return \CannyDain\Shorty\Config\ShortyConfiguration
     */
    public function getConfig()
    {
        return $this->_config;
    }
}