<?php
    require dirname(__FILE__).'/autoloader.php';

class InitialiseAutoloader
{
    public static function main()
    {
        $path = dirname(dirname(__FILE__)).'/';
        \CannyDain\Autoloader::Singleton()->RegisterRootPath($path);
        \CannyDain\Autoloader::Singleton()->Register();
    }
}

InitialiseAutoloader::main();