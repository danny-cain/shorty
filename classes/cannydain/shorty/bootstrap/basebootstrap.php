<?php

namespace CannyDain\Shorty\Bootstrap;

use CannyDain\Lib\CommentsManager\CommentsManager;
use CannyDain\Lib\CommentsManager\NullCommentsManager;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\Database\Interfaces\DatabaseConnection;
use CannyDain\Lib\Database\PDO\DatabaseConnections\PDOMySQLDatabaseConnection;
use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\DependencyInjection\Interfaces\DependencyFactoryInterface;
use CannyDain\Lib\Emailing\EmailerInterface;
use CannyDain\Lib\Emailing\NullEmailer;
use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Lib\GUIDS\SimpleGuidManager;
use CannyDain\Lib\Routing\Routers\DirectMappedRouter;
use CannyDain\Lib\UI\Response\Document\DocumentInterface;
use CannyDain\Lib\UI\Response\Response;
use CannyDain\Lib\UI\ResponsiveLayout\ResponsiveLayoutFactory;
use CannyDain\Lib\UI\ViewFactory;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Comments\ShortyCommentsManager;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\ECommerce\ECommerceManager;
use CannyDain\Shorty\ECommerce\Providers\NullProductProvider;
use CannyDain\Shorty\Execution\AppMain;
use CannyDain\Shorty\InstanceManager\InstanceManager;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\Shorty\Navigation\NavigationProvider;
use CannyDain\Shorty\Navigation\StaticNavigation;
use CannyDain\Shorty\Routing\URIManager;
use CannyDain\Shorty\Sidebars\SidebarManager;
use CannyDain\Shorty\Skinnable\Themes\Models\Theme;
use CannyDain\Shorty\Skinnable\Themes\ThemeManager;
use CannyDain\Shorty\TimeTracking\TimeTracker;
use CannyDain\Shorty\UI\Response\ShortyHTMLDocument;
use CannyDain\Shorty\UI\ViewHelpers\Factories\FormHelperFactory;
use CannyDain\Shorty\UserControl\UserControl;

class BaseBootstrap implements Bootstrap, DependencyFactoryInterface
{
    const CONSUMER_COMMENTS_MANAGER = '\\CannyDain\\Shorty\\Consumers\\CommentsConsumer';
    const CONSUMER_EMAILER = '\\CannyDain\\Shorty\\Consumers\\EmailerConsumer';
    const CONSUMER_NAVIGATION_PROVIDER = '\\CannyDain\\Shorty\\Consumers\\NavigationConsumer';
    const CONSUMER_GUID_MANAGER = '\\CannyDain\\Shorty\\Consumers\\GUIDManagerConsumer';
    const CONSUMER_RESPONSIVE_LAYOUT_FACTORY = '\\CannyDain\\Shorty\\Consumers\\ResponsiveLayoutConsumer';
    const CONSUMER_INSTANCE_MANAGER = '\\CannyDain\\Shorty\\Consumers\\InstanceManagerConsumer';
    const CONSUMER_USER_CONTROL = '\\CannyDain\\Shorty\\Consumers\\UserControlConsumer';
    const CONSUMER_VIEW_FACTORY = '\\CannyDain\\Shorty\\Consumers\\ViewFactoryConsumer';
    const CONSUMER_SIDEBAR_MANAGER = '\\CannyDain\\Shorty\\Consumers\\SidebarManagerConsumer';
    const CONSUMER_DATE_FORMAT_MANAGER = '\\CannyDain\\Shorty\\Consumers\\DateTimeConsumer';
    const CONSUMER_ECOMMERCE_MANAGER = '\\CannyDain\\Shorty\\Consumers\\ECommerceConsumer';
    const CONSUMER_URI_MANAGER = '\\CannyDain\\Shorty\\Consumers\\URIManagerConsumer';
    const CONSUMER_TIME_TRACKER = '\\CannyDain\\Shorty\\Consumers\\TimeEntryConsumer';

    /**
     * @var DependencyInjector
     */
    protected $_dependencyInjector;

    /**
     * @var TimeTracker
     */
    protected $_timeTracker;

    /**
     * @var CommentsManager
     */
    protected $_commentsManager;

    /**
     * @var SidebarManager
     */
    protected $_sidebarManager;

    /**
     * @var ViewFactory
     */
    protected $_viewFactory;

    /**
     * @var UserControl
     */
    protected $_userControl;

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    /**
     * @var InstanceManager
     */
    protected $_instanceManager;

    /**
     * @var ModuleManager
     */
    protected $_moduleManager;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var GUIDManagerInterface
     */
    protected $_guidManager;

    /**
     * @var RouterConsumer
     */
    protected $_router;

    /**
     * @var DatabaseConnection
     */
    protected $_database;

    /**
     * @var Response
     */
    protected $_response;

    /**
     * @var ResponsiveLayoutFactory
     */
    protected $_responsiveLayoutFactory;

    /**
     * @var NavigationProvider
     */
    protected $_navigationProvider;

    /**
     * @var ShortyConfiguration
     */
    protected $_config;

    /**
     * @var DateFormatManager
     */
    protected $_dateFormatManager;

    /**
     * @var ECommerceManager
     */
    protected $_ecommerce;

    /**
     * @var EmailerInterface
     */
    protected $_emailer;

    /**
     * @var URIManager
     */
    protected $_uriManager;

    public function createInstance($consumerInterface)
    {
        switch($consumerInterface)
        {
            case self::CONSUMER_COMMENTS_MANAGER:
                if ($this->_commentsManager == null)
                    $this->_commentsManager = $this->_factory_CommentsManager();

                $instance = $this->_commentsManager;
                break;
            case self::CONSUMER_EMAILER:
                if ($this->_emailer == null)
                    $this->_emailer = $this->_factory_Emailer();

                $instance = $this->_emailer;
                break;
            case self::CONSUMER_NAVIGATION_PROVIDER:
                if ($this->_navigationProvider == null)
                    $this->_navigationProvider = $this->_factory_NavigationProvider();

                $instance = $this->_navigationProvider;
                break;
            case self::CONSUMER_GUID_MANAGER:
                if ($this->_guidManager == null)
                    $this->_guidManager = $this->_factory_GUIDManager();

                $instance = $this->_guidManager;
                break;
            case self::CONSUMER_RESPONSIVE_LAYOUT_FACTORY:
                if($this->_responsiveLayoutFactory == null)
                    $this->_responsiveLayoutFactory = new ResponsiveLayoutFactory();

                $instance = $this->_responsiveLayoutFactory;
                break;
            case self::CONSUMER_INSTANCE_MANAGER:
                if ($this->_instanceManager == null)
                    $this->_instanceManager = new InstanceManager();

                $instance = $this->_instanceManager;
                break;
            case self::CONSUMER_USER_CONTROL:
                if ($this->_userControl == null)
                    $this->_userControl = new UserControl;

                $instance = $this->_userControl;
                break;
            case self::CONSUMER_VIEW_FACTORY:
                if ($this->_viewFactory == null)
                    $this->_viewFactory = $this->_factory_ViewFactory();

                $instance = $this->_viewFactory;
                break;
            case self::CONSUMER_SIDEBAR_MANAGER:
                if ($this->_sidebarManager == null)
                    $this->_sidebarManager = $this->_factory_SidebarManager();

                $instance = $this->_sidebarManager;
                break;
            case self::CONSUMER_DATE_FORMAT_MANAGER:
                if ($this->_dateFormatManager == null)
                    $this->_dateFormatManager = new DateFormatManager('j\<\s\u\p\>S\<\/\s\u\p\> F Y', 'H:i', 'j\<\s\u\p\>S\<\/\s\u\p\> F Y \@ H:i');

                $instance = $this->_dateFormatManager;
                break;
            case self::CONSUMER_ECOMMERCE_MANAGER:
                if ($this->_ecommerce == null)
                {
                    $this->_ecommerce = new ECommerceManager();
                    $this->_ecommerce->setProductProvider($this->_factory_ProductProvider());
                    $this->_registerPaymentProviders();
                }

                $instance = $this->_ecommerce;
                break;
            case self::CONSUMER_URI_MANAGER:
                if ($this->_uriManager == null)
                    $this->_uriManager = $this->_factory_URIManager();

                $instance = $this->_uriManager;
                break;
            case self::CONSUMER_TIME_TRACKER:
                if ($this->_timeTracker == null)
                    $this->_timeTracker = new TimeTracker();

                $instance = $this->_timeTracker;
                break;
            default:
                $instance = null;
        }

        if ($instance != null)
            $this->_dependencyInjector->applyDependencies($instance);

        return $instance;
    }

    public function executeBootstrap(ShortyConfiguration $config, AppMain $main)
    {
        $this->_config = $config;
        $this->_createObjects();
        $this->_setupDependencies();
        $this->_applyDependencies();
        $this->_connectDatabase();

        $this->_completeObjectSetup();

        $this->_dependencyInjector->applyDependencies($main);
        $main->main();
    }

    protected function _debugSetup()
    {

    }

    protected function _setupThemes()
    {
        ThemeManager::Singleton()->addTheme(new Theme(0, 'Shorty Blue', 'shorty.json', array
        (
            '/themes/simple-blue.css'
        )));

        ThemeManager::Singleton()->addTheme(new Theme(1, 'Shorty Green', 'shorty.json', array
        (
            '/themes/simple-green.css'
        )));

        ThemeManager::Singleton()->addTheme(new Theme(2, 'Shorty Red', 'shorty.json', array
        (
            '/themes/simple-red.css'
        )));

        ThemeManager::Singleton()->addTheme(new Theme(3, 'Bannered Shorty Blue', 'shorty-bannered.json', array
        (
            '/themes/simple-blue.css'
        )));

        ThemeManager::Singleton()->addTheme(new Theme(4, 'Bannered Shorty Green', 'shorty-bannered.json', array
        (
            '/themes/simple-green.css'
        )));

        ThemeManager::Singleton()->addTheme(new Theme(5, 'Bannered Shorty Red', 'shorty-bannered.json', array
        (
            '/themes/simple-red.css'
        )));
    }

    protected function _completeObjectSetup()
    {
        $this->_request->loadFromHTTPRequest('r');

        $document = $this->_factory_DefaultDocument();
        $this->_dependencyInjector->applyDependencies($document);
        $this->_response->setDocument($document);

        $this->_instanceManager->registerObjects();
        $this->_moduleManager->initialise();

        $this->_setupThemes();
        $this->_debugSetup();
    }

    protected function _connectDatabase()
    {
        $host =$this->_config->getValue(ShortyConfiguration::CONFIG_KEY_DATABASE_HOST);
        $database = $this->_config->getValue(ShortyConfiguration::CONFIG_KEY_DATABASE_NAME);
        $user = $this->_config->getValue(ShortyConfiguration::CONFIG_KEY_DATABASE_USER);
        $pass = $this->_config->getValue(ShortyConfiguration::CONFIG_KEY_DATABASE_PASS);

        $this->_database->connect($host, $user, $pass);
        $this->_database->selectDatabase($database);
    }

    protected function _createObjects()
    {
        $this->_dependencyInjector = new DependencyInjector();
        $this->_request = new Request();
        $this->_router = $this->_factory_Router();
        $this->_database = new PDOMySQLDatabaseConnection();
        $this->_response = $this->_factory_Response();
        $this->_datamapper = new DataMapper($this->_database);
        $this->_moduleManager = new ModuleManager();
    }

    protected function _setupDependencies()
    {
        $this->_dependencyInjector->defineDependency('\\CannyDain\\Shorty\\Consumers\\DependencyConsumer', $this->_dependencyInjector);
        $this->_dependencyInjector->defineDependency('\\CannyDain\\Shorty\\Consumers\\RequestConsumer', $this->_request);
        $this->_dependencyInjector->defineDependency('\\CannyDain\\Shorty\\Consumers\\RouterConsumer', $this->_router);
        $this->_dependencyInjector->defineDependency('\\CannyDain\\Shorty\\Consumers\\DatabaseConsumer', $this->_database);
        $this->_dependencyInjector->defineDependency('\\CannyDain\\Shorty\\Consumers\\ResponseConsumer', $this->_response);
        $this->_dependencyInjector->defineDependency('\\CannyDain\\Shorty\\Consumers\\DataMapperConsumer', $this->_datamapper);
        $this->_dependencyInjector->defineDependency('\\CannyDain\\Shorty\\Consumers\\ModuleConsumer', $this->_moduleManager);
        $this->_dependencyInjector->defineDependency('\\CannyDain\\Shorty\\Consumers\\ConfigurationConsumer', $this->_config);

        /* Lazy loaded dependencies */
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\URIManagerConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\TimeEntryConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\SidebarManagerConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\DateTimeConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\ECommerceConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\InstanceManagerConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\UserControlConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\ViewFactoryConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\ResponsiveLayoutConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\GUIDManagerConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\NavigationConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\EmailerConsumer', $this);
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\CommentsConsumer', $this);

        /* Dependency Factories */
        $this->_dependencyInjector->defineDependencyFactory('\\CannyDain\\Shorty\\Consumers\\FormHelperConsumer', $this->_factory_FormHelperFactory());
    }

    protected function _factory_FormHelperFactory()
    {
        return new FormHelperFactory();
    }

    protected function _applyDependencies()
    {
        $this->_dependencyInjector->applyDependencies($this->_request);
        $this->_dependencyInjector->applyDependencies($this->_router);
        $this->_dependencyInjector->applyDependencies($this->_database);
        $this->_dependencyInjector->applyDependencies($this->_response);
        $this->_dependencyInjector->applyDependencies($this->_datamapper);
        $this->_dependencyInjector->applyDependencies($this->_moduleManager);
        $this->_dependencyInjector->applyDependencies(ThemeManager::Singleton());
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