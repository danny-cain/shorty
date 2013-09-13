<?php

namespace CannyDain\Lib\Performance\Minifier;

class MinifyCSS extends Minifier
{
    public function bundleAndMinifyDirectory($dir)
    {
        return $this->bundleDirectory($dir);
    }
}