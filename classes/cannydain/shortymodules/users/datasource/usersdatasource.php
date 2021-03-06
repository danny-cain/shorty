<?php

namespace CannyDain\ShortyModules\Users\Datasource;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\ShortyModules\Users\Models\Session;
use CannyDain\ShortyModules\Users\Models\User;

class UsersDatasource extends ShortyDatasource
{
    public function createSession()
    {
        $model = new Session();
        $this->_dependencies->applyDependencies($model);

        return $model;
    }

    public function createUser()
    {
        $model = new User();
        $this->_dependencies->applyDependencies($model);

        return $model;
    }

    /**
     * @param $email
     * @return User
     */
    public function loadUserByEmail($email)
    {
        return array_shift($this->_datamapper->getObjectsWithCustomClauses(User::USER_OBJECT_NAME, array
        (
            'email LIKE :email'
        ), array
        (
            'email' => strtr($email, array('%' => '\%', '_' => '\_', '\\' => '\\\\'))
        )));
    }

    /**
     * @param $username
     * @return User
     */
    public function loadUserByUsername($username)
    {
        return array_shift($this->_datamapper->getObjectsWithCustomClauses(User::USER_OBJECT_NAME, array
        (
            'username LIKE :username'
        ), array
        (
            'username' => strtr($username, array('%' => '\%', '_' => '\_', '\\' => '\\\\'))
        )));
    }

    /**
     * @param $id
     * @return Session
     */
    public function loadSession($id)
    {
        return $this->_datamapper->loadObject(Session::SESSION_OBJECT_NAME, array('id' => $id));
    }

    /**
     * @param $term
     * @return User[]
     */
    public function searchUsers($term)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(User::USER_OBJECT_NAME, array
        (
            'username LIKE :username'
        ), array
        (
            'username' => '%'.$term.'%'
        ));
    }

    /**
     * @param $id
     * @return User
     */
    public function loadUser($id)
    {
        return $this->_datamapper->loadObject(User::USER_OBJECT_NAME, array('id' => $id));
    }

    public function registerObjects()
    {
        $file = dirname(__FILE__).'/datadictionary.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }
}