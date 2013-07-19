<?php

namespace CannyDain\Shorty\InstanceManager\Workers;

class ClassScanner
{
    public function scanForClasses($basePath)
    {
        echo 'scanning "'.$basePath.'"<br>';
        $lastChar = substr($basePath, strlen($basePath) - 1);
        if ($lastChar != '\\' && $lastChar != '/')
            $basePath .= DIRECTORY_SEPARATOR;

        return $this->_scanDirectory($basePath);
    }

    protected function _scanDirectory($directory)
    {
        $classes = array();

        $dir = opendir($directory);
        while ($entry = readdir($dir))
        {
            if ($entry == '.' || $entry == '..')
                continue;

            $fullPath = $directory.$entry;
            if (is_dir($fullPath))
                $classes = array_merge($classes, $this->_scanDirectory($fullPath.DIRECTORY_SEPARATOR));
            else
                $classes = array_merge($classes, $this->_scanFile($fullPath));
        }

        return $classes;
    }

    protected function _scanFile($file)
    {
        $namespace = '';
        $parts = explode('.', $file);
        if (strtolower(array_pop($parts)) != 'php')
            return array();

        $classes = array();
        $handle = fopen($file, 'r');

        $isClassName = false;
        $isNamespace = false;

        while ($line = fgets($handle))
        {
            $line = trim($line);
            $words = explode(' ', $line);

            foreach ($words as $word)
            {
                if ($isClassName)
                {
                    $isClassName = false;
                    $classes[] = '\\'.$namespace.$word;
                }
                elseif ($isNamespace)
                {
                    $isNamespace = false;

                    if (substr($word, strlen($word) - 1) == ';')
                        $word = substr($word, 0, strlen($word) - 1);

                    $namespace = $word.'\\';
                }
                else
                {
                    if (strtolower($word) == 'class')
                        $isClassName = true;
                    if (strtolower($word == 'namespace'))
                        $isNamespace  = true;
                }
            }
        }

        return $classes;
    }
}