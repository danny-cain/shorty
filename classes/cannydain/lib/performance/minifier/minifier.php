<?php

namespace CannyDain\Lib\Performance\Minifier;

class Minifier
{
    public function bundleDirectory($root, $firstScripts = array())
    {
        $ret = array();
        $root = strtr($root, array('\\' => '/'));
        if (substr($root, strlen($root) - 1) != '/')
            $root = $root.'/';

        foreach ($firstScripts as $script)
        {
            $path = $root.$script;
            if (!file_exists($path))
                continue;

            if (!is_file($path))
                continue;

            $ret[] = file_get_contents($path);
        }

        $dir = opendir($root);
        while ($node = readdir($dir))
        {
            if ($node == '.' || $node == '..')
                continue;

            if (in_array($node, $firstScripts))
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