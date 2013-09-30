<?php

namespace CannyDain\ShortyModules\CVLibrary\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\ShortyModules\CVLibrary\CVLibraryModule;
use CannyDain\ShortyModules\CVLibrary\Views\CVLibraryView;
use CannyDain\ShortyModules\CVLibrary\Views\PDFView;

class CVLibraryController extends ShortyModuleController
{
    const CONTROLLER_NAME = __CLASS__;

    public function Index()
    {
        $view = new CVLibraryView();

        $view->setScriptURI($this->_router->getURI(new Route(CVLibraryAPIController::CONTROLLER_NAME, 'getClientJS')));

        return $view;
    }

    public function PDF($cvID)
    {
        $cv = $this->_getModule()->getDatasource()->getCV($cvID);
        $qualifications = $this->_getModule()->getDatasource()->getQualificationsByCV($cvID);
        $experience = $this->_getModule()->getDatasource()->getWorkExperienceByCV($cvID);

        $view = new PDFView();
        $view->setCv($cv);
        $view->setQualifications($qualifications);
        $view->setExperience($experience);

        return $view;
    }

    protected function _getModuleClassname()
    {
        return CVLibraryModule::MODULE_NAME;
    }

    /**
     * @return CVLibraryModule
     */
    protected function _getModule()
    {
        return parent::_getModule(); // TODO: Change the autogenerated stub
    }
}