<?php

namespace CannyDain\ShortyModules\Content\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\ShortyModules\Content\ContentModule;
use CannyDain\ShortyModules\Content\Views\ContentPageView;
use CannyDain\ShortyModules\Todo\Models\TodoEntry;
use CannyDain\ShortyModules\Todo\TodoModule;
use CannyDain\ShortyModules\Todo\Views\TodoEditView;
use CannyDain\ShortyModules\Todo\Views\TodoListView;

class ContentController extends ShortyModuleController
{
    public function View($pageID)
    {
        $view = new ContentPageView();
        $view->setPage($this->_getModule()->getDatasource()->loadPage($pageID));

        return $view;
    }

    protected function _getModuleClassname()
    {
        return ContentModule::CONTENT_MODULE_CLASS;
    }

    /**
     * @return ContentModule
     */
    protected function _getModule()
    {
        return parent::_getModule();
    }
}