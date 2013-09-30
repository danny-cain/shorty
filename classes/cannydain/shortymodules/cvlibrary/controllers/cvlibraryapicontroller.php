<?php

namespace CannyDain\ShortyModules\CVLibrary\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\JSONView;
use CannyDain\Lib\UI\Views\PlainTextView;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Controllers\ShortyModuleController;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\ShortyModules\CVLibrary\CVLibraryModule;
use CannyDain\ShortyModules\CVLibrary\Models\CV;
use CannyDain\ShortyModules\CVLibrary\Models\Experience;
use CannyDain\ShortyModules\CVLibrary\Models\Qualification;

class CVLibraryAPIController extends ShortyModuleController implements SessionConsumer
{
    const CONTROLLER_NAME = __CLASS__;

    /**
     * @var SessionHelper
     */
    protected $_session;

    protected function _convertExperienceToAssociativeArray(Experience $experience)
    {
        return array
        (
            'id' => $experience->getId(),
            'cv' => $experience->getCv(),
            'description' => $experience->getDescription(),
            'start' => date('Y-m', $experience->getEmploymentStart()),
            'end' => date('Y-m', $experience->getEmploymentEnd()),
            'company' => $experience->getCompany(),
            'title' => $experience->getJobTitle(),
        );
    }

    protected function _getExperienceFromPost($prefix = '')
    {
        $id = $this->_request->getParameter($prefix.'id');
        $experience = null;

        if ($id != '')
            $experience = $this->_getModule()->getDatasource()->getExperience($id);

        if ($experience == null)
        {
            $experience = new Experience();
            $this->_dependencies->applyDependencies($experience);
        }

        $experience->setCv($this->_request->getParameterOrDefault($prefix.'cv', $experience->getCv()));
        $experience->setDescription($this->_request->getParameterOrDefault($prefix.'description', $experience->getDescription()));
        $experience->setEmploymentStart(strtotime($this->_request->getParameterOrDefault($prefix.'start', date('Y-m', $experience->getEmploymentStart()))."-01"));
        $experience->setEmploymentEnd(strtotime($this->_request->getParameterOrDefault($prefix.'end', date('Y-m', $experience->getEmploymentEnd()))."-01"));
        $experience->setCompany($this->_request->getParameterOrDefault($prefix.'company', $experience->getCompany()));
        $experience->setJobTitle($this->_request->getParameterOrDefault($prefix.'title', $experience->getJobTitle()));

        return $experience;
    }

    protected function _convertQualificationToAssociativeArray(Qualification $qualification)
    {
        return array
        (
            'id' => $qualification->getId(),
            'course' => $qualification->getCourse(),
            'cv' => $qualification->getCv(),
            'grade' => $qualification->getGrade(),
            'level' => $qualification->getLevel(),
            'year' => $qualification->getYear(),
        );
    }

    protected function _getQualificationFromPost($prefix = '')
    {
        $id = $this->_request->getParameter($prefix.'id');
        $qual = null;

        if ($id != '')
            $qual = $this->_getModule()->getDatasource()->getQualification($id);

        if ($qual == null)
        {
            $qual = new Qualification();
            $this->_dependencies->applyDependencies($qual);
        }

        $qual->setCourse($this->_request->getParameterOrDefault($prefix.'course', $qual->getCourse()));
        $qual->setCv($this->_request->getParameterOrDefault($prefix.'cv', $qual->getCv()));
        $qual->setGrade($this->_request->getParameterOrDefault($prefix.'grade', $qual->getGrade()));
        $qual->setLevel($this->_request->getParameterOrDefault($prefix.'level', $qual->getLevel()));
        $qual->setYear($this->_request->getParameterOrDefault($prefix.'year', $qual->getYear()));

        return $qual;
    }

    protected function _convertCVToAssociativeArray(CV $cv)
    {
        return array
        (
            'id' => $cv->getId(),
            'created' => date('Y-m-d H:i:s', $cv->getCreated()),
            'modified' => date('Y-m-d H:i:s', $cv->getModified()),
            'owner' => $cv->getUser(),
            'title' => $cv->getTitle(),
            'pageTitle' => $cv->getPageTitle(),
            'hobbies' => $cv->getHobbiesAndInterests(),
            'about' => $cv->getAboutMe(),
            'name' => $cv->getFullName(),
            'number' => $cv->getContactNumber(),
            'address' => $cv->getAddress()
        );
    }

    protected function _getCVFromPost()
    {
        $cv = null;
        $id = $this->_request->getParameter('id');
        if ($id > 0)
            $cv = $this->_getModule()->getDatasource()->getCV($id);

        if ($cv == null)
        {
            $cv = new CV();
            $this->_dependencies->applyDependencies($cv);

            $cv->setCreated(time());
            $cv->setUser($this->_session->getUserID());
        }

        $cv->setModified(time());
        $cv->setTitle($this->_request->getParameterOrDefault('title', $cv->getTitle()));
        $cv->setPageTitle($this->_request->getParameterOrDefault('pageTitle', $cv->getPageTitle()));
        $cv->setHobbiesAndInterests($this->_request->getParameterOrDefault('hobbies', $cv->getHobbiesAndInterests()));
        $cv->setAboutMe($this->_request->getParameterOrDefault('about', $cv->getAboutMe()));
        $cv->setFullName($this->_request->getParameterOrDefault('name', $cv->getFullName()));
        $cv->setContactNumber($this->_request->getParameterOrDefault('number', $cv->getContactNumber()));
        $cv->setAddress($this->_request->getParameterOrDefault('address', $cv->getAddress()));

        return $cv;
    }

    public function getClientJS()
    {
        $replacements = array
        (
            '#saveCVURI#' => $this->_router->getURI(new Route(__CLASS__, 'saveCV')),
            '#saveQualificationURI#' => $this->_router->getURI(new Route(__CLASS__, 'saveQualification')),
            '#saveWorkExperienceURI#' => $this->_router->getURI(new Route(__CLASS__, 'saveWorkExperience')),
            '#getAllCVsURI#' => $this->_router->getURI(new Route(__CLASS__, 'getAllCVs')),
            '#getCVURI#' => $this->_router->getURI(new Route(__CLASS__, 'getCV', array('#id#'))),
            '#getQualificationsURI#' => $this->_router->getURI(new Route(__CLASS__, 'getQualifications', array('#cv#'))),
            '#getWorkExperienceURI#' => $this->_router->getURI(new Route(__CLASS__, 'getWorkExperience', array('#cv#'))),
            '#deleteCVURI#' => $this->_router->getURI(new Route(__CLASS__, 'deleteCV', array('#id#'))),
            '#deleteExperienceURI#' => $this->_router->getURI(new Route(__CLASS__, 'deleteExperience', array('#id#'))),
            '#deleteQualificationURI#' => $this->_router->getURI(new Route(__CLASS__, 'deleteQualification', array('#id#'))),
            '#downloadURI#' => $this->_router->getURI(new Route(CVLibraryController::CONTROLLER_NAME, 'PDF', array('#id#'))),
        );

        $data = file_get_contents(dirname(dirname(__FILE__)).'/data/apiclient.js');

        $data = strtr($data, $replacements);

        return new PlainTextView($data, 'application/javascript');
    }

    public function deleteCV($id)
    {
        if (!$this->_request->isPost())
            return new JSONView(array('status' => 'failed', 'message' => 'Not a post'));

        $cv = $this->_getModule()->getDatasource()->getCV($id);
        if ($cv == null || $cv->getUser() != $this->_session->getUserID())
            return new JSONView(array('status' => 'failed', 'message' => 'Not your CV'));

        $this->_getModule()->getDatasource()->deleteCV($id);
        return new JSONView(array('status' => 'ok'));
    }

    public function deleteExperience($id)
    {
        if (!$this->_request->isPost())
            return new JSONView(array('status' => 'failed'));

        $exp = $this->_getModule()->getDatasource()->getExperience($id);
        if ($exp == null)
            return new JSONView(array('status' => 'failed'));

        $cv = $this->_getModule()->getDatasource()->getCV($exp->getCv());
        if ($cv == null || $cv->getUser() != $this->_session->getUserID())
            return new JSONView(array('status' => 'failed'));

        $this->_getModule()->getDatasource()->deleteExperience($id);
        return new JSONView(array('status' => 'ok'));
    }

    public function deleteQualification($id)
    {
        if (!$this->_request->isPost())
            return new JSONView(array('status' => 'failed'));

        $qual = $this->_getModule()->getDatasource()->getQualification($id);
        if ($qual == null)
            return new JSONView(array('status' => 'failed'));

        $cv = $this->_getModule()->getDatasource()->getCV($qual->getCv());
        if ($cv == null || $cv->getUser() != $this->_session->getUserID())
            return new JSONView(array('status' => 'failed'));

        $this->_getModule()->getDatasource()->deleteQualification($id);
        return new JSONView(array('status' => 'ok'));
    }

    public function saveCV()
    {
        if (!$this->_request->isPost())
            return new JSONView(array('status' => 'failed', 'message' => 'Not Posted'));

        $cv = $this->_getCVFromPost();
        if ($cv->getUser() != $this->_session->getUserID())
            return new JSONView(array('status' => 'failed', 'message' => 'Not Your CV'));

        $cv->save();
        return new JSONView(array('status' => 'ok', 'cv' => $this->_convertCVToAssociativeArray($cv)));
    }

    public function saveQualification()
    {
        if (!$this->_request->isPost())
            return new JSONView(array('status' => 'failed'));

        $qual = $this->_getQualificationFromPost();
        if ($qual->getCv() == '')
            return new JSONView(array('status' => 'failed'));

        $cv = $this->_getModule()->getDatasource()->getCV($qual->getCv());
        if ($cv == null || $cv->getUser() != $this->_session->getUserID())
            return new JSONView(array('status' => 'failed'));

        $qual->save();
        $cv->setModified(time());
        $cv->save();

        return new JSONView(array('status' => 'ok', 'qualification' => $this->_convertQualificationToAssociativeArray($qual)));
    }

    public function saveWorkExperience()
    {
        if (!$this->_request->isPost())
            return new JSONView(array('status' => 'failed'));

        $exp = $this->_getExperienceFromPost();
        if ($exp->getCv() == '')
            return new JSONView(array('status' => 'failed'));

        $cv = $this->_getModule()->getDatasource()->getCV($exp->getCv());
        if ($cv == null || $cv->getUser() != $this->_session->getUserID())
            return new JSONView(array('status' => 'failed'));

        $exp->save();
        $cv->setModified(time());
        $cv->save();

        return new JSONView(array('status' => 'ok', 'experience' => $this->_convertExperienceToAssociativeArray($exp)));
    }

    public function getAllCVs()
    {
        $ret = array();

        foreach ($this->_getModule()->getDatasource()->getAllCVsForUser() as $cv)
        {
            $ret[] = $this->_convertCVToAssociativeArray($cv);
        }

        return new JSONView($ret);
    }

    public function getCV($id)
    {
        $cv = $this->_getModule()->getDatasource()->getCV($id);

        if ($cv->getUser() != $this->_session->getUserID())
            return new JSONView(array());

        return new JSONView($this->_convertCVToAssociativeArray($cv));
    }

    public function getQualifications($cvID)
    {
        $cv = $this->_getModule()->getDatasource()->getCV($cvID);

        if ($cv->getUser() != $this->_session->getUserID())
            return new JSONView(array());

        $ret = array();

        foreach ($this->_getModule()->getDatasource()->getQualificationsByCV($cvID) as $qual)
        {
            $ret[] = $this->_convertQualificationToAssociativeArray($qual);
        }

        return new JSONView($ret);
    }

    public function getWorkExperience($cvID)
    {
        $cv = $this->_getModule()->getDatasource()->getCV($cvID);

        if ($cv->getUser() != $this->_session->getUserID())
            return new JSONView(array());

        $ret = array();

        foreach ($this->_getModule()->getDatasource()->getWorkExperienceByCV($cvID) as $experience)
        {
            $ret[] = $this->_convertExperienceToAssociativeArray($experience);
        }

        return new JSONView($ret);
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
        return parent::_getModule();
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }
}