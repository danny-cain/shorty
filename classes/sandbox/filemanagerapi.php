<?php

namespace Sandbox;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\Controllers\ShortyController;
use Sandbox\Views\FileManagerListView;

class FileManagerAPI extends ShortyController
{
    const CONTROLLER_NAME = __CLASS__;

    public function ls()
    {
        $dir = $this->_request->getParameter('dir');
        $page = $this->_request->getParameterOrDefault("page", 1);
        $resultsPerPage = $this->_request->getParameterOrDefault('resultsPerPage', 100);

        $dir = strtr($dir, array('..' => ''));
        $dir = strtr($dir, array('//' => '/'));

        if (substr($dir, strlen($dir) - 1) != '/')
            $dir .= '/';

        $path = $this->_getFileManagerRootPath().$dir;
        $resource = opendir($path);

        $ret = array();
        $count = 0;
        $start = $resultsPerPage * ($page - 1);

        while ($entry = readdir($resource))
        {
            if ($entry == '.' || $entry == '..')
                continue;

            if ($count >= $start && $count <= $start + $resultsPerPage)
            {
                $fullPath = $path.$entry;
                if (is_dir($fullPath))
                    $ret[] = array('type' => 'dir', 'name' => $entry, 'path' => $dir, 'webPath' => $dir.$entry);
                elseif (is_file($fullPath))
                    $ret[] = array('type' => 'file', 'name'=> $entry, 'path' => $dir, 'webPath' => $dir.$entry);
            }
            $count ++;
        }

        $view = new FileManagerListView();
        $view->setListDirectoryRoute(new Route(__CLASS__, 'ls'));
        $view->setListing($ret);
        $view->setPath($dir);

        $view->setDirectoryActions(array
        (
            'delete' => 'Delete'
        ));
        $view->setFileActions(array
        (
            'select' => 'Select',
            'delete' => 'Delete'
        ));

        $view->setIsFramed(true);

        return $view;
    }

    protected function _getFileManagerRootPath()
    {
        return dirname(dirname(dirname(__FILE__))).'/public/';
    }
}