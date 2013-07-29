<?php

namespace CannyDain\ShortyCoreModules\UserModule\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\DataMapping\Interfaces\ModelFactoryInterface;
use CannyDain\Lib\Database\Interfaces\DatabaseConnection;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\Consumers\DatabaseConsumer;
use CannyDain\ShortyCoreModules\UserModule\Models\GroupModel;
use CannyDain\ShortyCoreModules\UserModule\Models\SessionModel;
use CannyDain\ShortyCoreModules\UserModule\Models\SingleSignOnUserModel;
use CannyDain\ShortyCoreModules\UserModule\Models\UserModel;

class UserModuleDataLayer implements DataMapperConsumer, DatabaseConsumer, ModelFactoryInterface
{
    const OBJECT_USER = '\\CannyDain\\ShortyCoreModules\\UserModule\\Models\\UserModel';
    const OBJECT_SESSION = '\\CannyDain\\ShortyCoreModules\\UserModule\\Models\\SessionModel';
    const OBJECT_GROUP = '\\CannyDain\\ShortyCoreModules\\UserModule\\Models\\GroupModel';

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    /**
     * @var DatabaseConnection
     */
    protected $_database;

    /**
     * @param $type
     * @param array $rowData
     * @return object
     */
    public function createModel($type, $rowData)
    {
        if ($type != self::OBJECT_USER)
            return null;

        // check for Facebook users etc

        return null;
    }

    /**
     * @param $id
     * @return UserModel
     */
    public function getUserByID($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_USER, array('id' => $id));
    }

    public function timeoutSessions()
    {
        $activityTimeout = date('Y-m-d H:i:s', strtotime("-1 hour"));
        $loginTimeout = date('Y-m-d H:i:s', strtotime("-5 hours"));

        $sql = 'UPDATE '.$this->_datamapper->getTableNameForObject(self::OBJECT_SESSION).'
                SET valid = 0
                WHERE lastactive <= :activity
                OR started <= :started';
        $this->_database->statement($sql, array('activity' => $activityTimeout, 'started' => $loginTimeout));
    }

    /**
     * @param $searchTerm
     * @return UserModel[]
     */
    public function searchUsers($searchTerm)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_USER, array
        (
            'username LIKE :username'
        ), array
        (
            'username' => '%'.$searchTerm.'%',
        ), 'username ASC');
    }

    /**
     * @param $count
     * @return UserModel[]
     */
    public function getMostRecentRegistrations($count)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_USER, array(), array(), 'registered DESC', 0, $count);
    }

    /**
     * @param $username
     * @return UserModel
     */
    public function getUserByUsername($username)
    {
        $results = $this->_datamapper->getAllObjectsViaEqualityFilter(self::OBJECT_USER, array
        (
            'username' => $username
        ));
        if (count($results) == 0)
            return null;

        return $results[0];
    }

    /**
     * @param $searchTerm
     * @return GroupModel[]
     */
    public function searchGroups($searchTerm)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_GROUP, array
        (
            'name LIKE :search'
        ), array
        (
            'search' => '%'.$searchTerm.'%'
        ), 'name ASC');
    }

    /**
     * @param $id
     * @return GroupModel
     */
    public function getGroupByID($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_GROUP, array('id' => $id));
    }

    /**
     * @param $id
     * @return SessionModel
     */
    public function getSessionByID($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_SESSION, array('id' => $id));
    }

    public function saveGroup(GroupModel $group)
    {
        $this->_datamapper->saveObject($group);
    }

    public function saveUser(UserModel $user)
    {
        if ($user->getId() == 0)
            $user->setRegistrationDate(time());

        $this->_datamapper->saveObject($user);
    }

    public function saveSession(SessionModel $session)
    {
        $this->_datamapper->saveObject($session);
    }

    public function registerObjects()
    {
        $file = dirname(dirname(__FILE__)).'/datadictionary/objects.json';
        $builder = new JSONFileDefinitionBuilder();

        $builder->readFile($file, $this->_datamapper);
        $this->_datamapper->registerModelFactory(self::OBJECT_USER, $this);
    }

    public function dependenciesConsumed()
    {
        // TODO: Implement dependenciesConsumed() method.
    }

    public function consumeDatabaseConnection(DatabaseConnection $dependency)
    {
        $this->_database = $dependency;
    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}