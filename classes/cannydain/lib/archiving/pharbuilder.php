<?php

namespace CannyDain\Lib\Archiving;

class PharBuilder
{
    protected $_directoriesToAdd = array();
    protected $_filesToAdd = array();
    protected $_fileContents = array();

    public function addFileAsString($pharPath, $contents)
    {
        $this->_fileContents[$pharPath] = $contents;
    }

    public function addFile($file, $pharPath)
    {
        $this->_filesToAdd[$file] = $pharPath;
    }

    public function addDirectory($directory, $pharPath)
    {
        $this->_directoriesToAdd[$directory] = $pharPath;
    }

    public function compile($destination, $compression = \Phar::GZ)
    {
        if (file_exists($destination))
            unlink($destination);
        if (file_exists($destination.'.gz'))
            unlink($destination.'.gz');

        $phar = new \Phar($destination);

        foreach ($this->_fileContents as $pharPath => $contents)
            $phar->addFromString($pharPath, $contents);

        foreach ($this->_directoriesToAdd as $dir => $pharPath)
            $this->_addDirectory($phar, $dir, $pharPath);

        foreach ($this->_filesToAdd as $file => $pharPath)
            $this->_addFile($phar, $file, $pharPath);

        $phar->compress($compression);
    }

    protected function _addDirectory(\Phar $phar, $directory, $pharPath)
    {
        $phar->addEmptyDir($pharPath);
        $handle = opendir($directory);

        if (!is_resource($handle))
        {
            exit;
        }

        while ($file = readdir($handle))
        {
            if ($file == '.' || $file == '..')
                continue;

            $fullPath = $directory.$file;
            $fullPharPath = $pharPath.$file;

            if (is_dir($fullPath))
                $this->_addDirectory($phar, $fullPath.'/', $fullPharPath.'/');
            else
                $this->_addFile($phar, $fullPath, $fullPharPath);
        }
    }

    protected function _addFile(\Phar $phar, $file, $pharPath)
    {
        try
        {
            $phar->addFile($file, $pharPath);
        }
        catch(\Exception $e) {}
    }
}