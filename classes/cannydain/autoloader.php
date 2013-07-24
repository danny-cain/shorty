<?php

namespace CannyDain;

class Autoloader
{
    /**
     * @var Autoloader
     */
    private static $self = null;
    private $rootPaths = array();

    /**
     * @var AutoloaderListener[]
     */
    protected $_listeners = array();

    public function registerListener(AutoloaderListener $listener)
    {
        $this->_listeners[] = $listener;
    }

    public function RegisterRootPath($path)
    {
        $this->rootPaths[] = $path;
    }

    public function Register()
    {
        spl_autoload_register(array(self::Singleton(), 'Autoload'));
    }

    public function Unregister()
    {
        spl_autoload_unregister(array(self::Singleton(), 'Autoload'));
    }

    public function Autoload($class)
    {
        foreach ($this->_listeners as $listener)
            $listener->autoloadRequested($class);

        $relPath = strtolower(strtr($class, array('\\' => '/')));
        $attemptedPaths = array();

        foreach ($this->rootPaths as $path)
        {
            $classPath = $path.$relPath.'.php';
            $attemptedPaths[] = $classPath;

            if (!file_exists($classPath))
                continue;

            require $classPath;
            foreach ($this->_listeners as $listener)
                $listener->autoloadSucceeded($class, $classPath);
            return true;
        }

        foreach ($this->_listeners as $listener)
            $listener->autoloadFailed($class, $attemptedPaths);

        return false;
    }

    public static function Singleton()
    {
        if (self::$self == null)
            self::$self = new Autoloader();

        return self::$self;
    }

    private function __construct()
    {
    }

    private function __clone() {}
}

interface AutoloaderListener
{
    public function autoloadRequested($class);
    public function autoloadSucceeded($class, $path);
    public function autoloadFailed($class, $attemptedPaths);
}