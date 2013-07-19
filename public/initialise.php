<?php

// todo - need to find a way around file_exists not working from within a .phar (returns false even when looking at an existing directory/file external to the phar)
const USE_SOURCE = true; // if set to true will use /classes, otherwise will use /build/core.phar and /build/modules.phar

if (USE_SOURCE)
    require dirname(dirname(__FILE__)).'/classes/cannydain/initialise.php';
else
{
    require 'phar://'.dirname(dirname(__FILE__)).'/build/core.phar';
    require 'phar://'.dirname(dirname(__FILE__)).'/build/shorty.phar';
    require 'phar://'.dirname(dirname(__FILE__)).'/build/modules.phar';
}

define('SITE_DEV', 'dev');
define('SITE_DANNYCAIN_DEV', 'dannycain-dev');

class ShortyInit
{
    public static function main($main = null)
    {
        $init = new ShortyInit();

        if (!USE_SOURCE)
        {
            if (file_exists(dirname(dirname(__FILE__)).'/build/site.phar'))
                require 'phar://'.dirname(dirname(__FILE__)).'/build/site.phar';
            elseif (file_exists(dirname(dirname(__FILE__)).'/build/'.$init->getSiteID().'.phar'))
                require 'phar://'.dirname(dirname(__FILE__)).'/build/'.$init->getSiteID().'.phar';
        }

        date_default_timezone_set('Europe/London');
        error_reporting(E_ALL);
        //ini_set('log_errors', true);
        //ini_set('display_errors', false);
        //ini_set('error_log', 'error_log');

        if ($main == null)
        {
            switch($init->getSiteID())
            {
                case SITE_DANNYCAIN_DEV:
                    $main = new \CannyDain\Sites\DannyCain\DCMain();
                    break;
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
            case SITE_DANNYCAIN_DEV:
                $bootstrap = new \CannyDain\Sites\DannyCain\DCBootstrap();
                break;
            case SITE_DEV:
            default:
                $bootstrap = new \CannyDain\Shorty\Bootstrap\ShortyBootstrap();
        }

        return $bootstrap;
    }

    function getSiteID()
    {
        switch($_SERVER['SERVER_NAME'])
        {
            case 'danny.dannycain.goblin':
            case 'www.dannycain.com':
            case 'dannycain.com':
                return SITE_DANNYCAIN_DEV;
            case 'danny.shorty.goblin':
            case 'shorty.dannycain.com':
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
            case SITE_DANNYCAIN_DEV:
                $configFile .= 'dev/config.json';
                break;
            case SITE_DEV:
                $configFile .= 'dev/config.json';
                break;
        }

        return new \CannyDain\Shorty\Config\ShortyConfiguration($configFile, array
        (
            '#root#' => dirname(dirname(__FILE__))
        ));
    }
}