<?php

// todo - need to find a way around file_exists not working from within a .phar (returns false even when looking at an existing directory/file external to the phar)
const USE_SOURCE = true; // if set to true will use /classes, otherwise will use /build/core.phar and /build/modules.phar

if (USE_SOURCE)
    require dirname(dirname(__FILE__)).'/classes/cannydain/initialise.php';
else
{
    $pharBase = dirname(dirname(__FILE__)).'/build/';
    require 'phar://'.$pharBase.'core.phar';
    require 'phar://'.$pharBase.'shorty.phar';
    require 'phar://'.$pharBase.'modules.phar';
}

define('SITE_DEV', 'dev');

class ShortyInit
{
    public static function main($main = null)
    {
        $init = new ShortyInit();

        date_default_timezone_set('Europe/London');
        error_reporting(E_STRICT);
        ini_set('log_errors', true);
        ini_set('display_errors', true);
        ini_set('error_log', 'error_log');

        if ($main == null)
        {
            switch($init->getSiteID())
            {
                case SITE_DEV:
                    $main = new \CannyDain\Shorty\Execution\ShortyMain();
                    break;
            }
        }

        $init->initialiseAndExecute($main);
    }

    public function initialiseAndExecute(\CannyDain\Shorty\Execution\AppMain $main)
    {
        $bootstrap = $this->getBootstrap();
        $config = $this->getConfig();

        $bootstrap->executeBootstrap($config, $main);
    }

    private function getBootstrap()
    {
        switch($this->getSiteID())
        {
            case SITE_DEV:
                $bootstrap = new \CannyDain\Shorty\Bootstrap\ShortyBootstrap();
        }

        return $bootstrap;
    }

    function getSiteID()
    {
        switch($_SERVER['SERVER_NAME'])
        {
            default:
                return SITE_DEV;
                break;
        }
    }

    private function getConfig()
    {
        $configFile = dirname(dirname(__FILE__)).'/private/';

        switch($this->getSiteID())
        {
            default:
                $configFile .= 'dev/config.json';
                break;
        }

        return new \CannyDain\Shorty\Config\ShortyConfiguration($configFile, array
        (
            '#root#' => dirname(dirname(__FILE__))
        ));
    }
}