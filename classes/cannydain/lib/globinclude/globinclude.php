<?php

namespace CannyDain\Lib\GlobInclude;

class GlobInclude
{
    protected $_rootDir = '';
    protected $_includedFiles = array();
    protected $_phpExtensions = array('php', 'php4', 'ph4', 'php3', 'ph3', 'php5', 'ph5');

    public function __construct($rootDir = '')
    {
        $this->setRootDir($rootDir);
    }

    public function includeFiles()
    {
        $this->_includedFiles = array();
        $this->_scanDirectory($this->_rootDir);
        return $this->_includedFiles;
    }

    protected function _scanDirectory($directory)
    {
        $dir = opendir($directory);
        while ($node = readdir($dir))
        {
            if ($node == '.' || $node == '..')
                continue;

            $fullPath = $directory.$node;
            if (is_file($fullPath))
                $this->_processFile($fullPath);
            elseif (is_dir($fullPath))
                $this->_scanDirectory($fullPath.'/');
        }
    }

    protected function _processFile($file)
    {
        if (!$this->_canIncludeFile($file))
            return;

        $this->_includedFiles[] = $file;
        require_once $file;
    }

    protected function _canIncludeFile($file)
    {
        $parts = explode('/', $file);
        $filename = array_pop($parts);
        $parts = explode('.', $filename);

        if (count($parts) == 1)
            return false;

        $extension = strtolower(array_pop($parts));
        if (in_array($extension, $this->_phpExtensions))
            return true;

        return false;
    }

    public static function includeDirectory($directory)
    {

    }

    public function setRootDir($rootDir)
    {
        $rootDir = strtr($rootDir, array('\\' => '/'));
        if (substr($rootDir, strlen($rootDir) - 1) != '/')
            $rootDir .= '/';
        
        $this->_rootDir = $rootDir;
    }

    public function getRootDir()
    {
        return $this->_rootDir;
    }
}