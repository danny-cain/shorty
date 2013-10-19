<?php

namespace CannyDain\Shorty\FileManager\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Consumers\FileManagerConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\Shorty\FileManager\FileManagerInterface;
use CannyDain\Shorty\FileManager\Views\FileManagerView;

class FileManagerController extends ShortyController implements FileManagerConsumer
{
    const CONTROLLER_NAME = __CLASS__;

    /**
     * @var FileManagerInterface
     */
    protected $_fileManager;

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

        return $view;
    }

    public function consumeFileManager(FileManagerInterface $fileManager)
    {
        $this->_fileManager = $fileManager;
    }
}