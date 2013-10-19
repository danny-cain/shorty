<?php

namespace CannyDain\Shorty\FileManager;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Shorty\FileManager\Models\FileModel;

interface FileManagerInterface
{
    const ACTION_SELECT = 'select';
    const ACTION_LINK = 'link';
    const ACTION_IMAGE = 'image';

    /**
     * @param $directory
     * @param array $fileActions
     * @param array $directoryActions
     * @return Route
     */
    public function getFileManagerFrameRoute($directory = '/', $fileActions = array(), $directoryActions = array());

    /**
     * @param string $directory
     * @return Route
     */
    public function getFileManagerRoute($directory = '/');

    /**
     * @param $fileManagerPath
     * @return FileModel[]
     */
    public function listDir($fileManagerPath);
}