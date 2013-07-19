<?php

use CannyDain\Shorty\InstanceManager\InstanceManager;

require dirname(__FILE__).'/initialise.php';

class CompilerMain implements \CannyDain\Shorty\Execution\AppMain, \CannyDain\Shorty\Consumers\InstanceManagerConsumer
{
    /**
     * @var \CannyDain\Shorty\InstanceManager\InstanceManager
     */
    protected $_instanceManager;

    /**
     * @var \CannyDain\Lib\DependencyInjection\DependencyInjector
     */
    protected $_dependencies;

    protected $_buildPath = '';
    protected $_modules = array();

    /**
     * @var \CannyDain\Shorty\InstanceManager\Models\BaseTypeDefinition
     */
    protected $_moduleType;

    protected function _setup()
    {
        $this->_moduleType = $this->_instanceManager->getTypeByInterfaceOrClassname('\CannyDain\Shorty\Modules\Interfaces\ModuleInterface');
    }

    public function main()
    {
        $this->_setup();
        $this->_displayMainMenu();
    }

    protected function _addModule()
    {
        echo "Available Modules:\r\n";

        $modules = $this->_instanceManager->getInstancesByType($this->_moduleType->getId());
        foreach ($modules as $module)
        {
            if (in_array($module->getId(), $this->_modules))
                continue;

            echo $module->getId().': '.$module->getClassName()."\r\n";
        }

        echo "Module ID to add (0 to cancel): ";
        $id = intval(fgets(STDIN));

        if ($id >= 0)
            $this->_modules[$id] = $id;

        $this->_displayMainMenu();
    }

    protected function _removeModule()
    {
        echo "Added modules:\r\n";

        foreach ($this->_modules as $id)
        {
            if ($id <= 0)
                continue;

            $name = $this->_instanceManager->getInstanceByID($id)->getClassName();
            echo $id.": ".$name."\r\n";
        }

        echo "Enter an id to remove (or 0 to cancel): ";
        $id = intval(fgets(STDIN));

        if ($id > 0)
            unset($this->_modules[$id]);

        $this->_displayMainMenu();
    }

    protected function _compile()
    {
        $file = $this->_getValidPathToBuildTo();
        if ($file == null)
        {
            $this->_displayMainMenu();
            return;
        }

        $pharBuilder = new \CannyDain\Lib\Archiving\PharBuilder();

        $rootPath = dirname(dirname(__FILE__)).'/';
        $pharBuilder->addFile($rootPath.'classes/cannydain/autoloader.php', '/cannydain/autoloader.php');
        $pharBuilder->addFile($rootPath.'classes/cannydain/initialise.php', '/cannydain/initialise.php');

        $pharBuilder->addDirectory($rootPath.'classes/cannydain/lib/', '/cannydain/lib/');
        $pharBuilder->addDirectory($rootPath.'classes/cannydain/shorty/', '/cannydain/shorty/');
        foreach ($this->_modules as $module)
        {
            $name = $this->_instanceManager->getInstanceByID($module)->getClassName();
            $relPath = dirname(strtolower(strtr($name, array('\\' => '/'))).'.php');

            $pharBuilder->addDirectory($rootPath.'classes'.$relPath.'/', $relPath.'/');
        }
        $pharBuilder->addFileAsString('index.php', $this->_getPharStub());

        $pharBuilder->compile($file);
    }

    protected function _getPharStub()
    {
        return <<<PHP
<?php
    require dirname(__FILE__).'/cannydain/initialise.php';

PHP;
    }
    protected function _getValidPathToBuildTo()
    {
        echo "Enter the location to create the phar (leave blank to cancel):";
        $path = trim(fgets(STDIN));
        $directory = dirname($path);

        if ($path == '')
            return null;

        $parts = explode('.', $path);
        if (strtolower(array_pop($parts)) != 'phar')
        {
            echo "Target location must end in .phar\r\n";
            return $this->_getValidPathToBuildTo();
        }

        if (!file_exists($directory))
            mkdir($directory, 0755, true);

        $pharExists = file_exists($path);
        $gzExists = file_exists($path.'.gz');

        if ($pharExists || $gzExists)
        {
            echo "Target phar and or gz already exist, overwrite? (y/n):";
            if (trim(fgets(STDIN)) == 'n')
                return $this->_getValidPathToBuildTo();

            if ($pharExists)
                unlink($path);

            if ($gzExists)
                unlink($path.'.gz');
        }



        return $path;
    }

    protected function _displayMainMenu()
    {
        echo "Shorty Compiler\r\n";
        echo "Please select an option from below:\r\n";
        echo "1. Add a module\r\n";
        echo "2. Remove a module\r\n";
        echo "3. Compile\r\n";
        echo "0. Exit\r\n";

        echo "Enter option: ";
        $option = fgets(STDIN);
        switch(trim($option))
        {
            case "1":
                $this->_addModule();
                break;
            case "2":
                $this->_removeModule();
                break;
            case "3":
                $this->_compile();
                break;
            case "0":
                exit;
            default:
                $this->_displayMainMenu();
        }
    }

    public function dependenciesConsumed()
    {
        // TODO: Implement dependenciesConsumed() method.
    }

    public function consumeInstanceManager(InstanceManager $dependency)
    {
        $this->_instanceManager = $dependency;
    }
}

ShortyInit::main(new CompilerMain());