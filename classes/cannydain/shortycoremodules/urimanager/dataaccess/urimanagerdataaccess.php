<?php

namespace CannyDain\ShortyCoreModules\URIManager\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\ShortyCoreModules\URIManager\Models\URIMappingModel;

class URIManagerDataAccess implements DataMapperConsumer
{
    const OBJECT_URI = '\\CannyDain\\ShortyCoreModules\\URIManager\\Models\\URIMappingModel';

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    public function deleteURI($id)
    {
        $this->_datamapper->deleteObject(self::OBJECT_URI, array('id' => $id));
    }

    public function saveURI(URIMappingModel $uri)
    {
        $this->_datamapper->saveObject($uri);
    }

    public function countAllURIs()
    {
        return $this->_datamapper->countObjects(self::OBJECT_URI);
    }

    /**
     * @param int $resultsPerPage
     * @param int $page
     * @return URIMappingModel
     */
    public function getAllURIs($resultsPerPage = 25, $page = 1)
    {
        $startRec = ($page - 1) * $resultsPerPage;
        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_URI, array(), array(),'uri ASC', $startRec, $resultsPerPage);
    }

    /**
     * @param Route $route
     * @return URIMappingModel
     */
    public function getExactMatchRoute(Route $route)
    {
        $results = $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_URI, array
        (
            'controller LIKE :controller',
            'method LIKE :method',
            'params LIKE :params'
        ), array
        (
            'controller' => $route->getController(),
            'method' => $route->getMethod(),
            'params' => json_encode($route->getParams())
        ));

        return array_shift($results);
    }

    /**
     * @param Route $route
     * @return URIMappingModel
     */
    public function getBestMatchRoute(Route $route)
    {
        $controller = $route->getController();
        if (substr($controller, 0, 1) != '\\')
            $controller = '\\'.$controller;

        $results = $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_URI, array
        (
            '(LCASE(controller) = :controller OR controller = "")',
            '(method LIKE :method OR method = "")',
        ), array
        (
            'controller' => strtolower($controller),
            'method' => $route->getMethod()
        ));

        $bestMatchURI = null;
        $matchStrength = -1;

        foreach ($results as $result)
        {
            $strength = $this->_compareRouteWithURIEntryAndReturnStrength($route, $result);
            if ($strength > $matchStrength)
            {
                $matchStrength = $strength;
                $bestMatchURI = $result;
            }
        }

        return $bestMatchURI;
    }

    protected function _compareRouteWithURIEntryAndReturnStrength(Route $route, URIMappingModel $entry)
    {
        $routeController = $route->getController();
        $entryController = $entry->getController();

        if (substr($routeController, 0, 1) != '\\')
            $routeController = '\\'.$routeController;
        if (substr($entryController, 0, 1) != '\\')
            $entryController = '\\'.$entryController;

        if (strtolower($route->getMethod()) != strtolower($entry->getMethod()) && $entry->getMethod() != '')
            return -1;

        if (strtolower($routeController) != strtolower($entryController) && $entry->getController() != '')
            return -1;

        if (count($entry->getParams()) > count($route->getParams()))
            return -1;

        $entryParams = $entry->getParams();
        $routeParams = $route->getParams();

        for ($i = 0; $i < count($entry->getParams()); $i ++)
        {
            if (strtolower($entryParams[$i]) != strtolower($routeParams[$i]))
                return -1;
        }

        if ($entry->getController() == '')
            return 0;

        if ($entry->getMethod() == '')
            return 1;

        return count($entry->getParams()) + 1;
    }

    /**
     * @param $uri
     * @return URIMappingModel
     */
    public function getBestMatchURI($uri)
    {
        $results = $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_URI, array
        (
            ':uri LIKE CONCAT(uri, "%")'
        ),array
        (
            'uri' => $uri
        ), 'LENGTH(uri) DESC');

        return array_shift($results);
    }

    /**
     * @param $id
     * @return URIMappingModel
     */
    public function getURI($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_URI, array('id' => $id));
    }

    public function registerObjects()
    {
        $file = dirname(dirname(__FILE__)).'/datadictionary/objects.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}