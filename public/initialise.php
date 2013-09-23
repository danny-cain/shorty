<?php
    date_default_timezone_set('Etc/UTC');

function miniBootstrap()
{
    $autoloaderInclude = dirname(dirname(__FILE__)).'/classes/cannydain/autoloader.php';
    if ($autoloaderInclude !== null)
    {
        require $autoloaderInclude;
        \CannyDain\Autoloader::Singleton()->Register();
    }

    $rootPath = dirname(dirname(__FILE__)).'/';
    $includes = array
    (
        //'phar://'.$rootPath.'lib/core.phar',
    );

    $rootPaths = array
    (
        $rootPath.'classes/',
    );

    foreach ($includes as $include)
        require $include;

    foreach ($rootPaths as $path)
        \CannyDain\Autoloader::Singleton()->RegisterRootPath($path);
}
miniBootstrap();

use CannyDain\Shorty\Bootstrap\BaseBootstrap;

class ShortyInit
{
    protected function _includes()
    {
        // comment this out to disable debug output
        \CannyDain\Autoloader::Singleton()->registerListener(new \CannyDain\Shorty\Debug\AutoloaderListener());
    }

    /**
     * @return BaseBootstrap
     */
    protected function _getBootstrap() { return new \CannyDain\Shorty\Bootstrap\DevBootstrap(); }

    /**
     * @return \CannyDain\Shorty\Config\ShortyConfiguration
     */
    protected function _getConfig()
    {
        $publicRoot = dirname(__FILE__).'/';
        $root = dirname($publicRoot).'/';
        $path = $root.'private/';

        switch($_SERVER['SERVER_NAME'])
        {
            case 'danny.shorty2.goblin':
            case 'danny.shorty.goblin':
            default:
                $path .= 'shorty2/config.json';
                break;
        }

        $data = "{}";
        if (file_exists($path))
            $data = file_get_contents($path);

        $data = json_decode($data, true);

        if (!is_array($data))
            $data = array();

        $config = new \CannyDain\Shorty\Config\ShortyConfiguration();
        $config->setConfiguration($data);

        $config->setValue(\CannyDain\Shorty\Config\ShortyConfiguration::KEY_FILE_SYSTEM_ROOT, $root);
        $config->setValue(\CannyDain\Shorty\Config\ShortyConfiguration::KEY_PUBLIC_ROOT, $publicRoot);
        $config->setValue(\CannyDain\Shorty\Config\ShortyConfiguration::KEY_STYLE_ROOT, $publicRoot.'styles/');
        $config->setValue(\CannyDain\Shorty\Config\ShortyConfiguration::KEY_SCRIPT_ROOT, $publicRoot.'scripts/');
        $config->setValue(\CannyDain\Shorty\Config\ShortyConfiguration::KEY_PRIVATE_DATA_ROOT, $root.'private/');

        return $config;
    }

    protected function _bootstrap($dependentObjects = array())
    {
        $bootstrap = $this->_getBootstrap();

        $bootstrap->setConfig($this->_getConfig());

        if (strtolower(substr($_SERVER['SERVER_NAME'], 0, 6)) == 'danny.')
            $bootstrap->setCheckDatastructures(true);

        if (isset($_GET['dsCheck']) && $_GET['dsCheck'] == 1)
            $bootstrap->setCheckDatastructures(true);

        $bootstrap->executeBootstrap($dependentObjects);
    }

    public static function Initialise($dependentObjects = array())
    {
        $init = new ShortyInit();

        $init->_includes();
        $init->_bootstrap($dependentObjects);
    }
}