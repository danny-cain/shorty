<?php

namespace CannyDain\ShortyModules\CVLibrary\Datasource;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\ShortyModules\CVLibrary\Models\CV;
use CannyDain\ShortyModules\CVLibrary\Models\Experience;
use CannyDain\ShortyModules\CVLibrary\Models\Qualification;

class CVLibraryDatasource extends ShortyDatasource implements SessionConsumer
{
    /**
     * @var SessionHelper
     */
    protected $_session;

    public function deleteQualification($id)
    {
        $this->_datamapper->deleteObject(Qualification::OBJECT_TYPE_QUALIFICATION, array('id' => $id));
    }

    public function deleteExperience($id)
    {
        $this->_datamapper->deleteObject(Experience::OBJECT_TYPE_EXPERIENCE, array('id' => $id));
    }

    public function deleteCV($id)
    {
        foreach($this->getQualificationsByCV($id) as $qual)
            $this->deleteQualification($qual->getId());

        foreach($this->getWorkExperienceByCV($id) as $exp)
            $this->deleteExperience($exp->getId());

        $this->_datamapper->deleteObject(CV::OBJECT_TYPE_CV, array('id' => $id));
    }

    /**
     * @param $id
     * @return CV
     */
    public function getCV($id)
    {
        return $this->_datamapper->loadObject(CV::OBJECT_TYPE_CV, array('id' => $id));
    }

    /**
     * @param $id
     * @return Qualification
     */
    public function getQualification($id)
    {
        return $this->_datamapper->loadObject(Qualification::OBJECT_TYPE_QUALIFICATION, array('id' => $id));
    }

    /**
     * @param $id
     * @return Experience
     */
    public function getExperience($id)
    {
        return $this->_datamapper->loadObject(Experience::OBJECT_TYPE_EXPERIENCE, array('id' => $id));
    }

    /**
     * @param null $userID
     * @return CV[]
     */
    public function getAllCVsForUser($userID = null)
    {
        if ($userID == null)
            $userID =$this->_session->getUserID();

        if ($userID < 1)
            return array();

        return $this->_datamapper->getObjectsWithCustomClauses(CV::OBJECT_TYPE_CV, array
        (
            'user = :user'
        ), array
        (
            'user' => $userID
        ));
    }

    /**
     * @param $cvID
     * @return Experience[]
     */
    public function getWorkExperienceByCV($cvID)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(Experience::OBJECT_TYPE_EXPERIENCE, array
        (
            'cv = :cv'
        ), array
        (
            'cv' => $cvID
        ));
    }

    /**
     * @param $cvID
     * @return Qualification[]
     */
    public function getQualificationsByCV($cvID)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(Qualification::OBJECT_TYPE_QUALIFICATION, array
        (
            'cv = :cv'
        ), array
        (
            'cv' => $cvID
        ));
    }

    public function registerObjects()
    {
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile(dirname(__FILE__).'/datadictionary.json', $this->_datamapper);
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }
}