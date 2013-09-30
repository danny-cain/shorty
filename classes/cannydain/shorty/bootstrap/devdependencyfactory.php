<?php

namespace CannyDain\Shorty\Bootstrap;

use CannyDain\Lib\Database\Listeners\FileLoggerQueryListener;
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
use CannyDain\ShortyModules\ObjectPermissions\Manager\ObjectPermissionsManager;
use CannyDain\ShortyModules\ObjectPermissions\ObjectPermissionsModule;
use CannyDain\ShortyModules\ShortyBasket\Helpers\ShortyBasketHelper;
use CannyDain\ShortyModules\ShortyBasket\ShortyBasketModule;
use CannyDain\ShortyModules\SimpleShop\Providers\SimpleShopProductProvider;
use CannyDain\ShortyModules\SimpleShop\SimpleShopModule;
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

    protected function _factory_database()
    {
        $database = parent::_factory_database();

        $logFile = dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/sql.log';

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


    protected function _factory_modules()
    {
        $moduleManager = parent::_factory_modules();

        $moduleManager->loadModule(new TodoModule());
        $moduleManager->loadModule(new UsersModule());
        $moduleManager->loadModule(new CommentsModule());
        $moduleManager->loadModule(new ContentModule());
        $moduleManager->loadModule(new SimpleShopModule());
        $moduleManager->loadModule(new InvoiceModule());
        $moduleManager->loadModule(new ShortyBasketModule());
        $moduleManager->loadModule(new TasksModule());
        $moduleManager->loadModule(new ObjectPermissionsModule());
        $moduleManager->loadModule(new FinanceModule());
        $moduleManager->loadModule(new CVLibraryModule());

        return $moduleManager;
    }

}