<?php

namespace CannyDain\Shorty\FileManager;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\FileManager\Controllers\FileManagerController;
use CannyDain\Shorty\FileManager\Models\FileModel;

class FileManager implements FileManagerInterface
{
    protected $_webRootPath = '';
    protected $_privateRootPath = '';
    protected $_fileSystemRoot = '';

    public function __construct($webRoot, $fileSystemRoot, $privateRoot)
    {
        $this->_webRootPath = realpath($webRoot);
        $this->_fileSystemRoot = realpath($fileSystemRoot);
        $this->_privateRootPath = realpath($privateRoot);
    }

    /**
     * @param string $directory
     * @param array $fileActions
     * @param array $directoryActions
     *
     * @return Route
     */
    public function getFileManagerFrameRoute($directory = '/', $fileActions = array(), $directoryActions = array())
    {
        $route = new Route(FileManagerController::CONTROLLER_NAME, 'BrowseFrame', array
        (

        ), array
        (
            'directory' => $directory,
            'fileActions' => $fileActions,
            'directoryActions' => $directoryActions
        ));

        return $route;
    }

    /**
     * @param string $directory
     * @return Route
     */
    public function getFileManagerRoute($directory = '/')
    {
        $route = new Route(FileManagerController::CONTROLLER_NAME, 'Browse', array
        (

        ), array
        (
            'directory' => $directory,
        ));

        return $route;
    }

    /**
     * @param $fileManagerPath
     * @return FileModel[]
     */
    public function listDir($fileManagerPath)
    {
        $path = realpath($this->_fileSystemRoot.$fileManagerPath);
        if (!$this->_isPathAccessible($path))
            return array();

        $dir = opendir($path);
        $ret = array();

        while ($entry = readdir($dir))
        {
            if ($entry == '.' || $entry == '..')
                continue;

            $fullPath = $path.DIRECTORY_SEPARATOR.$entry;
            if(!$this->_isPathAccessible($fullPath))
                continue;

            $model = $this->_processEntryForListing($fullPath, $entry);

            if ($model != null)
                $ret[] = $model;
        }

        usort($ret, function($a, $b)
        {
            /**
             * @var FileModel $a
             * @var FileModel $b
             */
            if ($a->getType() == FileModel::TYPE_DIRECTORY && $b->getType() == FileModel::TYPE_FILE)
                return 1;

            if ($a->getType() == FileModel::TYPE_FILE && $b->getType() == FileModel::TYPE_DIRECTORY)
                return -1;

            if ($a->getName() > $b->getName())
                return 1;

            if ($a->getName() < $b->getName())
                return -1;

            return 0;
        });

        if (strtolower($path) != strtolower($this->_fileSystemRoot))
        {
            array_unshift($ret, $this->_processEntryForListing(realpath(dirname($path)), '[up a directory]'));
        }

        return $ret;
    }

    protected function _isPathAccessible($fullPath)
    {
        $check = substr($fullPath, 0, strlen($this->_fileSystemRoot));

        if (strtolower($check) != strtolower($this->_fileSystemRoot))
            return false;

        return true;
    }

    /**
     * @param $fullPath
     * @param $name
     * @return FileModel
     */
    protected function _processEntryForListing($fullPath, $name)
    {
        $isWebAccessibleCheck = substr($fullPath, 0, strlen($this->_webRootPath));
        $webPath = '';

        if (strtolower($isWebAccessibleCheck) == strtolower($this->_webRootPath))
            $webPath = substr($fullPath, strlen($this->_webRootPath));

        $webPath = strtr($webPath, array(DIRECTORY_SEPARATOR => '/'));

        $fileSystemPath = substr($fullPath, strlen($this->_fileSystemRoot));
        if (substr($fileSystemPath, 0, 1) != DIRECTORY_SEPARATOR)
            $fileSystemPath = DIRECTORY_SEPARATOR.$fileSystemPath;
        $fileSystemPath = strtr($fileSystemPath, array(DIRECTORY_SEPARATOR => '/'));

        $model = new FileModel();
        $model->setWebPath($webPath);
        $model->setPath($fileSystemPath);
        $model->setName($name);

        if (is_dir($fullPath))
            $model->setType(FileModel::TYPE_DIRECTORY);
        elseif (is_file($fullPath))
            $model->setType(FileModel::TYPE_FILE);
        else
            return null;

        return $model;
    }
}