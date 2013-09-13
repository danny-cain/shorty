<?php

namespace CannyDain\Lib\Performance\Minifier;

class Minifier
{
    public function bundleDirectory($root)
    {
        $ret = array();
        $root = strtr($root, array('\\' => '/'));
        if (substr($root, strlen($root) - 1) != '/')
            $root = $root.'/';

        $dir = opendir($root);
        while ($node = readdir($dir))
        {
            if ($node == '.' || $node == '..')
                continue;

            $fullPath = $root.$node;
            if (is_dir($fullPath))
                $ret[] = $this->bundleDirectory($fullPath.'/');
            if (is_file($fullPath))
                $ret[] = file_get_contents($fullPath);
        }

        return implode("\r\n", $ret);
    }
}