<?php

namespace CannyDain\Shorty\Bootstrap;

use CannyDain\Lib\Database\Listeners\FileLoggerQueryListener;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\Routing\Routers\CompositeRouter;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Consumers\ModuleConsumer;
use CannyDain\Shorty\Modules\ModuleManager;
use CannyDain\Shorty\Routing\MappedModuleRouter;
use CannyDain\Shorty\Routing\Models\ModuleMap;
use CannyDain\ShortyModules\AddressManagement\AddressManagementModule;
use CannyDain\ShortyModules\AddressManagement\Managers\ShortyAddressManager;
use CannyDain\ShortyModules\CVLibrary\CVLibraryModule;
use CannyDain\ShortyModules\Comments\CommentsModule;
use CannyDain\ShortyModules\Comments\EventHandlers\NewCommentEmailHandler;
use CannyDain\ShortyModules\Comments\Managers\CommentsManager;
use CannyDain\ShortyModules\Content\ContentModule;
use CannyDain\ShortyModules\Finance\FinanceModule;
use CannyDain\ShortyModules\Finance\Providers\FinanceObjectProvider;
use CannyDain\ShortyModules\Invoice\Controllers\InvoiceController;
use CannyDain\ShortyModules\Invoice\InvoiceModule;
use CannyDain\ShortyModules\Invoice\Manager\InvoiceManager;
use CannyDain\ShortyModules\MailHistory\Emailer\MailHistoryEmailerWrapper;
use CannyDain\ShortyModules\MailHistory\MailHistoryModule;
use CannyDain\ShortyModules\ObjectPermissions\Manager\ObjectPermissionsManager;
use CannyDain\ShortyModules\ObjectPermissions\ObjectPermissionsModule;
use CannyDain\ShortyModules\ShortyBasket\Helpers\ShortyBasketHelper;
use CannyDain\ShortyModules\ShortyBasket\ShortyBasketModule;
use CannyDain\ShortyModules\SimpleShop\Providers\SimpleShopProductProvider;
use CannyDain\ShortyModules\SimpleShop\SimpleShopModule;
use CannyDain\ShortyModules\Stories\StoriesModule;
use CannyDain\ShortyModules\Tasks\Controllers\TasksController;
use CannyDain\ShortyModules\Tasks\Providers\TasksObjectRegistryProvider;
use CannyDain\ShortyModules\Tasks\Providers\TasksPermissionsInfoProvider;
use CannyDain\ShortyModules\Tasks\TasksModule;
use CannyDain\ShortyModules\Comments\Providers\CommentsObjectRegistryProvider;
use CannyDain\ShortyModules\Content\Providers\ContentObjectRegistryProvider;
use CannyDain\ShortyModules\Invoice\Providers\InvoiceObjectRegistryProvider;
use CannyDain\ShortyModules\SimpleShop\Providers\SimpleShopObjectRegistryProvider;
use CannyDain\ShortyModules\Todo\Providers\TodoObjectRegistryProvider;
use CannyDain\ShortyModules\Todo\TodoModule;
use CannyDain\ShortyModules\Users\Helpers\UsersModuleSessionHelper;
use CannyDain\ShortyModules\Users\Helpers\UsersModuleUserHelper;
use CannyDain\ShortyModules\Users\Providers\UserObjectRegistryProvider;
use CannyDain\ShortyModules\Users\UsersModule;

class DevDependencyFactory extends BaseDependencyFactory
{
    protected function _factory_guidManager()
    {
        $manager = parent::_factory_guidManager();

        $manager->registerObjectRegistry(new CommentsObjectRegistryProvider());
        $manager->registerObjectRegistry(new ContentObjectRegistryProvider());
        $manager->registerObjectRegistry(new InvoiceObjectRegistryProvider());
        $manager->registerObjectRegistry(new SimpleShopObjectRegistryProvider());
        $manager->registerObjectRegistry(new TasksObjectRegistryProvider());
        $manager->registerObjectRegistry(new TodoObjectRegistryProvider());
        $manager->registerObjectRegistry(new UserObjectRegistryProvider());
        $manager->registerObjectRegistry(new FinanceObjectProvider());
        // todo turn this into an event, have modulemanager listen to event and auto-subscribe module object registries

        return $manager;
    }

    protected function _factory_addressManager()
    {
        return new ShortyAddressManager();
    }


    protected function _factory_database()
    {
        $database = parent::_factory_database();

        $logFile = $this->_config->getValue(ShortyConfiguration::KEY_PRIVATE_DATA_ROOT).'/sql.log';

        $database->registerQueryListener(new FileLoggerQueryListener($logFile, true));

        return $database;
    }

    protected function _factory_objectPermissions()
    {
        $manager = new ObjectPermissionsManager();

        $manager->registerProvider(new TasksPermissionsInfoProvider());
        // todo turn this into an event, have modulemanager listen to event and auto-subscribe module permissions managers

        return $manager;
    }

    protected function _factory_productManager()
    {
        $manager = parent::_factory_productManager();

        $manager->registerProvider($this->_dependencyInjector->applyDependencies(new SimpleShopProductProvider()));

        return $manager;
    }

    protected function _factory_basketHelper()
    {
        return new ShortyBasketHelper();
    }

    protected function _factory_invoiceManager()
    {
        return new InvoiceManager();
    }

    protected function _factory_paymentManager()
    {
        $manager = parent::_factory_paymentManager();

        $manager->registerProvider(InvoiceController::GetPaymentProvider());

        return $manager;
    }


    protected function _factory_accessControl()
    {
        return parent::_factory_accessControl();
    }

    protected function _factory_eventManager()
    {
        $eventManager = parent::_factory_eventManager();

        $commentEmailHandler = new NewCommentEmailHandler('DannyCain - Info', 'no-reply@dannycain.com', 'Danny Cain', 'danny@dannycain.com', 'New Comment was posted', "<p>#subject#</p><p>#comment#</p>");;
        $this->_dependencyInjector->applyDependencies($commentEmailHandler);
        // bind events here
        $eventManager->subscribeToEvents($commentEmailHandler, array(CommentsModule::EVENT_COMMENT_POSTED));

        return $eventManager;
    }

    protected function _factory_emailer()
    {
        return new MailHistoryEmailerWrapper(null);
    }


    protected function _factory_comments()
    {
        return new CommentsManager();
    }

    protected function _factory_session()
    {
        return new UsersModuleSessionHelper();
    }

    protected function _factory_userHelper()
    {
        return new UsersModuleUserHelper();
    }

    protected function _initialiseModules(ModuleManager $moduleManager)
    {
        $moduleManager->loadModule(new TodoModule());
        $moduleManager->loadModule(new UsersModule(new Route(TasksController::CONTROLLER_NAME)));
        $moduleManager->loadModule(new CommentsModule());
        $moduleManager->loadModule(new ContentModule());
        $moduleManager->loadModule(new SimpleShopModule());
        $moduleManager->loadModule(new InvoiceModule());
        $moduleManager->loadModule(new ShortyBasketModule());
        $moduleManager->loadModule(new TasksModule());
        $moduleManager->loadModule(new ObjectPermissionsModule());
        $moduleManager->loadModule(new FinanceModule());
        $moduleManager->loadModule(new CVLibraryModule());
        $moduleManager->loadModule(new AddressManagementModule());
        $moduleManager->loadModule(new StoriesModule(''));
        $moduleManager->loadModule(new MailHistoryModule());
    }

    protected function _factory_router()
    {
        $mappedRouter = new MappedModuleRouter(array
        (
            new ModuleMap(TodoModule::TODO_MODULE_CLASS, 'todo', TodoModule::CONTROLLER_NAMESPACE),
            new ModuleMap(UsersModule::USERS_MODULE_CLASS, 'users', UsersModule::CONTROLLER_NAMESPACE),
            new ModuleMap(CommentsModule::COMMENTS_MODULE_CLASS, 'comments', CommentsModule::CONTROLLER_NAMESPACE),
            new ModuleMap(ContentModule::CONTENT_MODULE_CLASS, 'content', ContentModule::CONTROLLER_NAMESPACE),
            new ModuleMap(SimpleShopModule::SIMPLE_SHOP_MODULE_NAME, 'shop', SimpleShopModule::CONTROLLER_NAMESPACE),
            new ModuleMap(InvoiceModule::INVOICE_MODULE_NAME, 'invoices', InvoiceModule::CONTROLLER_NAMESPACE),
            new ModuleMap(ShortyBasketModule::SHORTY_BASKET_MODULE_NAME, 'basket', ShortyBasketModule::CONTROLLER_NAMESPACE),
            new ModuleMap(TasksModule::TASKS_MODULE_NAME, 'tasks', TasksModule::CONTROLLER_NAMESPACE),
            new ModuleMap(ObjectPermissionsModule::OBJECT_PERMISSIONS_MODULE_NAME, 'object-permissions', ObjectPermissionsModule::CONTROLLER_NAMESPACE),
            new ModuleMap(FinanceModule::FINANCE_MODULE_NAME, 'finance', FinanceModule::CONTROLLER_NAMESPACE),
            new ModuleMap(CVLibraryModule::MODULE_NAME, 'cv', CVLibraryModule::CONTROLLER_NAMESPACE),
            new ModuleMap(AddressManagementModule::MODULE_NAME, 'addresses', AddressManagementModule::CONTROLLER_NAMESPACE),
            new ModuleMap(StoriesModule::STORY_MODULE_NAME, 'stories', StoriesModule::CONTROLLER_NAMESPACE),
            new ModuleMap(MailHistoryModule::MODULE_NAME, 'mail', MailHistoryModule::CONTROLLER_PATH),
        ));

        $fallbackRouter = parent::_factory_router();
        return new CompositeRouter(array($mappedRouter,$fallbackRouter));
    }


}