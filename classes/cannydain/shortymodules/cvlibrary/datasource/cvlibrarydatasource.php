<?php

namespace CannyDain\ShortyModules\CVLibrary\Datasource;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\Database\Interfaces\DatabaseConnection;
use CannyDain\Shorty\Consumers\DatabaseConsumer;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\ShortyModules\CVLibrary\Models\CV;
use CannyDain\ShortyModules\CVLibrary\Models\CVCategory;
use CannyDain\ShortyModules\CVLibrary\Models\Experience;
use CannyDain\ShortyModules\CVLibrary\Models\Qualification;

class CVLibraryDatasource extends ShortyDatasource implements SessionConsumer, DatabaseConsumer
{
    /**
     * @var DatabaseConnection
     */
    protected $_database;

    /**
     * @var SessionHelper
     */
    protected $_session;

    /**
     * @param $categories
     * @return CV[]
     */
    public function getCVsByCategories($categories = array())
    {
        $params = array();
        $placeholders = array();

        foreach ($categories as $index => $cat)
        {
            $placeholders[] = ':cat'.$index;
            $params['cat'.$index] = $cat;
        }
        return $this->_datamapper->getObjectsViaLink(CV::OBJECT_TYPE_CV, CVCategory::OBJECT_TYPE_CV_CATEGORY, array
        (
            'link.id IN ('.implode(', ', $placeholders).')'
        ), $params);
    }

    public function setCategoriesForCV($cvID, $categories)
    {
        // todo - this needs to be moved to the datamapper class
        //        (functionality needs proper planning first)

        $table = $this->_datamapper->getLinkTableName(CV::OBJECT_TYPE_CV, CVCategory::OBJECT_TYPE_CV_CATEGORY);
        $sql = 'DELETE FROM `'.$table.'` WHERE cvID = :cv';
        $this->_database->statement($sql, array('cv' => $cvID));

        foreach ($categories as $cat)
        {
            $sql = 'INSERT INTO `'.$table.'` SET cvID = :cv, catID = :cat';
            $this->_database->statement($sql, array('cv' => $cvID, 'cat' => $cat));
        }
    }

    /**
     * @param $cvID
     * @return CVCategory[]
     */
    public function getCategoriesForCV($cvID)
    {
        return $this->_datamapper->getObjectsViaLink(CVCategory::OBJECT_TYPE_CV_CATEGORY, CV::OBJECT_TYPE_CV, array
        (
            'link.id = :cv'
        ), array('cv' => $cvID));
    }

    /**
     * @return CVCategory
     */
    public function getAllCategories()
    {
        return $this->_datamapper->getObjectsWithCustomClauses(CVCategory::OBJECT_TYPE_CV_CATEGORY, array(), array(), 'name ASC');
    }

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

    public function consumeDatabase(DatabaseConnection $database)
    {
        $this->_database = $database;
    }
}