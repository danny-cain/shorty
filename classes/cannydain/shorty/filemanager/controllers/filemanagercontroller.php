<?php

namespace CannyDain\Shorty\FileManager\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\FileManagerConsumer;
use CannyDain\Shorty\Consumers\RouteManagerConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\FileManager\FileManagerInterface;
use CannyDain\Shorty\FileManager\Models\PageTypeModel;
use CannyDain\Shorty\FileManager\Views\FileManagerView;
use CannyDain\Shorty\Routing\RouteManager;

class FileManagerController extends ShortyController implements FileManagerConsumer, RouteManagerConsumer
{
    const CONTROLLER_NAME = __CLASS__;

    /**
     * @var FileManagerInterface
     */
    protected $_fileManager;

    /**
     * @var RouteManager
     */
    protected $_routeManager;

    public function Browse()
    {
        $dir = $this->_request->getParameter('directory');

        $view = new FileManagerView();
        $view->setPath($dir);
        $view->setDirectoryActions(array());
        $view->setFileActions(array());
        $view->setListDirectoryRoute(new Route(__CLASS__, 'Browse', array(), array
        (
            'directory' => '#dir#',
        )));

        $view->setListing($this->_fileManager->listDir($dir));
        $view->setIsFramed(false);

        $types = array();
        foreach ($this->_routeManager->getTypes() as $type)
        {
            $typeProvider = $this->_routeManager->getProvider($type);
            $model = new PageTypeModel($typeProvider->getTypeName(), $typeProvider->getType(), $typeProvider->browse(1, 100));
            $types[] = $model;
        }

        $view->setPageTypes($types);


        return $view;
    }

    public function BrowseFrame()
    {
        $dir = $this->_request->getParameter('directory');

        $view = new FileManagerView();
        $view->setPath($dir);
        $view->setDirectoryActions($this->_request->getParameter('directoryActions'));
        $view->setFileActions($this->_request->getParameter('fileActions'));
        $view->setListDirectoryRoute(new Route(__CLASS__, 'BrowseFrame', array(), array
        (
            'directory' => '#dir#',
            'directoryActions' => $view->getDirectoryActions(),
            'fileActions' => $view->getFileActions()
        )));

        $view->setListing($this->_fileManager->listDir($dir));

        $view->setIsFramed(true);


        $types = array();
        foreach ($this->_routeManager->getTypes() as $type)
        {
            $typeProvider = $this->_routeManager->getProvider($type);
            $model = new PageTypeModel($typeProvider->getTypeName(), $typeProvider->getType(), $typeProvider->browse(1, 100));
            $types[] = $model;
        }

        $view->setPageTypes($types);

        return $view;
    }

    public function consumeFileManager(FileManagerInterface $fileManager)
    {
        $this->_fileManager = $fileManager;
    }

    public function consumeRouteManager(RouteManager $manager)
    {
        $this->_routeManager = $manager;
    }
}