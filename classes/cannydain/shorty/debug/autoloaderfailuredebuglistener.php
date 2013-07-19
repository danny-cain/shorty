<?php

namespace CannyDain\Shorty\Debug;

use CannyDain\AutoloaderListener;

class AutoloaderFailureDebugListener implements AutoloaderListener
{
    public function autoloadRequested($class)
    {
        // TODO: Implement autoloadRequested() method.
    }

    public function autoloadSucceeded($class, $path)
    {
        // TODO: Implement autoloadSucceeded() method.
    }

    public function autoloadFailed($class, $attemptedPaths)
    {
        echo '<h1>Failed to load "'.$class.'"</h1>';
        echo '<pre>'.htmlentities(print_r($attemptedPaths, true), ENT_COMPAT, 'UTF-8').'</pre>';
    }
}