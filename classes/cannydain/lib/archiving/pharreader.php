<?php

namespace CannyDain\Lib\Archiving;

class PharReader
{
    protected $_inputFile = '';
    /**
     * @var \Phar
     */
    protected $_phar;

    public function __construct($inputFile)
    {
        $this->_inputFile = $inputFile;
        $this->_phar = new \Phar($inputFile);
    }

    public function getPharDirectoryContents($path)
    {
        if ($path == '' || $path == '/' || $path == '\\')
            return $this->_readPharRootContents();

        return $this->_readPharSubdirContents($path);
    }

    protected function _readPharRootContents()
    {
        $ret = array();
        /**
         * @var \PharFileInfo $entry
         */
        foreach ($this->_phar as $entry)
        {
            $ret[] = $entry->getFilename();
        }

        return $ret;
    }

    protected function _readPharSubdirContents($path)
    {
        $ret = array();

        $path = 'phar://'.$this->_phar->getPath().'/'.$path;

        if (!is_dir($path))
            return array();

        $resource = opendir($path);

        /**
         * @var \PharFileInfo $child
         */
        while ($entry = readdir($resource))
        {
            if ($entry == '.' || $entry == '..')
                continue;

            $ret[] = $entry;
        }

        return $ret;
    }
}