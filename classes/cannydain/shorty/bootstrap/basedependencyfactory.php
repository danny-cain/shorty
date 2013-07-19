<?php

namespace CannyDain\Shorty\Bootstrap;

use CannyDain\Lib\CommentsManager\CommentsManager;
use CannyDain\Lib\CommentsManager\NullCommentsManager;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\Database\PDO\DatabaseConnections\PDOMySQLDatabaseConnection;
use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\DependencyInjection\Interfaces\DependencyFactoryInterface;
use CannyDain\Lib\Emailing\NullEmailer;
use CannyDain\Lib\GUIDS\SimpleGuidManager;
use CannyDain\Lib\Routing\Routers\DirectMappedRouter;
use CannyDain\Lib\UI\Response\Document\DocumentInterface;
use CannyDain\Lib\UI\Response\Response;
use CannyDain\Lib\UI\ResponsiveLayout\ResponsiveLayoutFactory;
use CannyDain\Lib\UI\ViewFactory;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\ECommerce\ECommerceManager;
use CannyDain\Shorty\ECommerce\Providers\NullProductProvider;
use CannyDain\Shorty\InstanceManager\InstanceManager;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\Shorty\Navigation\StaticNavigation;
use CannyDain\Shorty\Routing\URIManager;
use CannyDain\Shorty\Sidebars\SidebarManager;
use CannyDain\Shorty\TimeTracking\TimeTracker;
use CannyDain\Shorty\UI\Response\ShortyHTMLDocument;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\Shorty\UserControl\UserControl;

abstract class BaseDependencyFactory implements DependencyFactoryInterface
{
    const CONSUMER_COMMENTS = '\\CannyDain\\Shorty\\Consumers\\CommentsConsumer';
    const CONSUMER_CONFIG = '\\CannyDain\\Shorty\\Consumers\\ConfigurationConsumer';
    const CONSUMER_DATABASE = '\\CannyDain\\Shorty\\Consumers\\DatabaseConsumer';
    const CONSUMER_DATAMAPPER = '\\CannyDain\\Shorty\\Consumers\\DataMapperConsumer';
    const CONSUMER_DATETIME = '\\CannyDain\\Shorty\\Consumers\\DateTimeConsumer';
    const CONSUMER_ECOMMERCE = '\\CannyDain\\Shorty\\Consumers\\ECommerceConsumer';
    const CONSUMER_EMAILER = '\\CannyDain\\Shorty\\Consumers\\EmailerConsumer';
    const CONSUMER_FORM_HELPER = '\\CannyDain\\Shorty\\Consumers\\FormHelperConsumer';
    const CONSUMER_GUID_MANAGER = '\\CannyDain\\Shorty\\Consumers\\GUIDManagerConsumer';
    const CONSUMER_INSTANCE_MANAGER = '\\CannyDain\\Shorty\\Consumers\\InstanceManagerConsumer';
    const CONSUMER_MODULES = '\\CannyDain\\Shorty\\Consumers\\ModuleConsumer';
    const CONSUMER_NAVIGATION = '\\CannyDain\\Shorty\\Consumers\\NavigationConsumer';
    const CONSUMER_REQUEST = '\\CannyDain\\Shorty\\Consumers\\RequestConsumer';
    const CONSUMER_RESPONSE = '\\CannyDain\\Shorty\\Consumers\\ResponseConsumer';
    const CONSUMER_RESPONSIVE_LAYOUT = '\\CannyDain\\Shorty\\Consumers\\ResponsiveLayoutConsumer';
    const CONSUMER_ROUTER = '\\CannyDain\\Shorty\\Consumers\\RouterConsumer';
    const CONSUMER_SIDEBAR_MANAGER = '\\CannyDain\\Shorty\\Consumers\\SidebarManagerConsumer';
    const CONSUMER_TIME_ENTRY = '\\CannyDain\\Shorty\\Consumers\\TimeEntryConsumer';
    const CONSUMER_URI_MANAGER = '\\CannyDain\\Shorty\\Consumers\\URIManagerConsumer';
    const CONSUMER_USER_CONTROL = '\\CannyDain\\Shorty\\Consumers\\UserControlConsumer';
    const CONSUMER_VIEW_FACTORY = '\\CannyDain\\Shorty\\Consumers\\ViewFactoryConsumer';

    /**
     * @var ShortyConfiguration
     */
    protected $_config;
    protected $_loadedInstances = array();
    /**
     * @var DependencyInjector
     */
    protected $_dependencyInjector;

    public function __construct(ShortyConfiguration $config, DependencyInjector $dependencies)
    {
        $this->_config = $config;
        $this->_dependencyInjector = $dependencies;
    }

    public function registerDependencies()
    {
        $dependencyInjector = $this->_dependencyInjector;
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_COMMENTS, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_CONFIG, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_DATABASE, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_DATAMAPPER, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_DATETIME, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_ECOMMERCE, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_EMAILER, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_FORM_HELPER, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_GUID_MANAGER, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_INSTANCE_MANAGER, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_MODULES, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_NAVIGATION, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_REQUEST, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_RESPONSE, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_RESPONSIVE_LAYOUT, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_ROUTER, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_SIDEBAR_MANAGER, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_TIME_ENTRY, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_URI_MANAGER, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_USER_CONTROL, $this);
        $dependencyInjector->defineDependencyFactory(self::CONSUMER_VIEW_FACTORY, $this);
    }

    public function createInstance($consumerInterface)
    {
        if (isset($this->_loadedInstances[$consumerInterface]))
            return $this->_loadedInstances[$consumerInterface];

        $instance = null;
        switch($consumerInterface)
        {
            case self::CONSUMER_COMMENTS:
                $instance = $this->_factory_CommentsManager();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_CONFIG:
                $instance = $this->_config;
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_DATABASE:
                $instance = new PDOMySQLDatabaseConnection();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_DATAMAPPER:
                $instance = new DataMapper($this->createInstance(self::CONSUMER_DATABASE));
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_DATETIME:
                $instance = new DateFormatManager('j\<\s\u\p\>S\<\/\s\u\p\> F Y', 'H:i', 'j\<\s\u\p\>S\<\/\s\u\p\> F Y \@ H:i');
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_ECOMMERCE:
                $instance = new ECommerceManager();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_EMAILER:
                $instance = $this->_factory_Emailer();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_FORM_HELPER:
                $instance = new FormHelper();
                break;
            case self::CONSUMER_GUID_MANAGER:
                $instance = $this->_factory_GUIDManager();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_INSTANCE_MANAGER:
                $instance = new InstanceManager();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_MODULES:
                $instance = new ModuleManager();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_NAVIGATION:
                $instance = $this->_factory_NavigationProvider();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_REQUEST:
                $instance = new Request();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_RESPONSE:
                $instance = $this->_factory_Response();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_RESPONSIVE_LAYOUT:
                $instance = new ResponsiveLayoutFactory();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_ROUTER:
                $instance = $this->_factory_Router();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_SIDEBAR_MANAGER:
                $instance = $this->_factory_SidebarManager();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_TIME_ENTRY:
                $instance = new TimeTracker();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_URI_MANAGER:
                $instance = $this->_factory_URIManager();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_USER_CONTROL:
                $instance = new UserControl();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            case self::CONSUMER_VIEW_FACTORY:
                $instance = $this->_factory_ViewFactory();
                $this->_loadedInstances[$consumerInterface] = $instance;
                break;
            default:
                throw new \Exception("Unknown consumer \"".$consumerInterface."\"");
        }

        $this->_dependencyInjector->applyDependencies($instance);

        return $instance;
    }

    /**
     * @return ViewFactory
     */
    protected function _factory_ViewFactory()
    {
        $factory = new ViewFactory();

        return $factory;
    }

    /**
     * @return Response
     */
    protected function _factory_Response()
    {
        return new Response();
    }

    /**
     * @return SidebarManager
     */
    protected function _factory_SidebarManager()
    {
        return new SidebarManager();
    }

    /**
     * @return DocumentInterface
     */
    protected function _factory_DefaultDocument()
    {
        return new ShortyHTMLDocument;
    }

    /**
     * @return CommentsManager
     */
    protected function _factory_CommentsManager()
    {
        return new NullCommentsManager();
    }

    /**
     * @return SimpleGuidManager
     */
    protected function _factory_GUIDManager()
    {
        return new SimpleGuidManager();
    }

    protected function _factory_Router()
    {
        return new DirectMappedRouter();
    }

    protected function _factory_ProductProvider()
    {
        return new NullProductProvider();
    }

    protected function _registerPaymentProviders()
    {

    }

    protected function _factory_Emailer()
    {
        return new NullEmailer();
    }

    protected function _factory_NavigationProvider()
    {
        return new StaticNavigation(array
        (
            '/' => 'Home'
        ));
    }

    protected function _factory_URIManager()
    {
        return new URIManager();
    }
}