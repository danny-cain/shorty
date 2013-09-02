<?php

namespace CannyDain\Shorty\Debug;

class AutoloaderListener implements \CannyDain\AutoloaderListener
{
    public function autoloadRequested($class)
    {

    }

    public function autoloadSucceeded($class, $path)
    {

    }

    public function autoloadFailed($class, $attemptedPaths)
    {
        echo 'Failed to load '.$class.':<br>';
        echo '<pre>'.print_r($attemptedPaths, true).'</pre>';
    }
}