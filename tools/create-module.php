<?php

use CannyDain\Lib\DependencyInjection\DependencyInjector;

require dirname(__FILE__).'/initialise.php';

class ModuleCreatorMain implements \CannyDain\Shorty\Execution\AppMain, \CannyDain\Shorty\Consumers\DependencyConsumer
{

    /**
     * @var \CannyDain\Lib\DependencyInjection\DependencyInjector
     */
    protected $_dependencies;

    protected $_moduleName = '';

    public function main()
    {
        global $argv;

        $scriptName = array_shift($argv);
        $this->_moduleName = array_shift($argv);

        $this->_create();
    }

    protected function _create()
    {
        if ($this->_moduleName == '')
        {
            echo "Please enter a name for the module: ";
            $this->_moduleName = trim(fgetss(STDIN));
        }

        $namespace = 'CannyDain\\ShortyCoreModules\\'.$this->_moduleName;
        $rootPath = dirname(dirname(__FILE__)).'/classes/cannydain/shortycoremodules/'.strtolower($this->_moduleName).'/';

        if (!file_exists($rootPath))
            mkdir($rootPath, 0777, true);

        // build directory structure
        mkdir($rootPath.'installer/');
        mkdir($rootPath.'controllers/');
        mkdir($rootPath.'dataaccess/');
        mkdir($rootPath.'datadictionary/');
        mkdir($rootPath.'models/');
        mkdir($rootPath.'views/');

        foreach ($this->_getFileContents($namespace) as $path => $contents)
        {
            file_put_contents($rootPath.$path, $contents);
        }

        echo "Done!\r\n";
    }

    protected function _getFileContents($namespace)
    {
        $author = 'Danny Cain';
        $authorWebsite = 'www.dannycain.com';
        $releaseDate = time();
        $version ='1.0.0';
        $name = $this->_moduleName;

        $dataAccessClassname = $this->_moduleName.'DataAccess';
        $installerName = $namespace.'\Installer\\'.$this->_moduleName.'Installer';
        ob_start();
        echo <<<PHP
<?php
namespace {$namespace};

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Shorty\Modules\Interfaces\ModuleInterface;
use CannyDain\Shorty\Modules\BaseModule;
use CannyDain\Shorty\Modules\Models\ModuleInfo;
use $namespace\DataAccess\\$dataAccessClassname;

use $installerName;

class {$this->_moduleName}Module extends BaseModule implements DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected \$_dependencies;

    /**
     * @return ModuleInstallerInterface
     */
    public function getInstaller() { return new {$this->_moduleName}Installer(); }

    public function initialise()
    {
        \$this->datasource()->registerObjects();
    }

    public function enable() {}
    public function disable() {}

    /**
     * @return ModuleInfo
     */
    public function getInfo()
    {
        \$info = new ModuleInfo();
        \$info->setAuthor("$author");
        \$info->setAuthorWebsite("$authorWebsite");
        \$info->setName("$name");
        \$info->setReleaseDate($releaseDate);
        \$info->setVersion("$version");

        return \$info;
    }

    /**
     * @return array
     */
    public function getControllerNames() { return array(); }

    protected function datasource()
    {
        static \$datasource = null;

        if (\$datasource == null)
        {
            \$datasource = new $dataAccessClassname();
            \$this->_dependencies->applyDependencies(\$datasource);
        }

        return \$datasource;
    }

    public function dependenciesConsumed() {}
    public function consumeDependencyInjector(DependencyInjector \$dependency) { \$this->_dependencies = \$dependency; }
}
PHP;
        $moduleFileContents = ob_get_clean();

        ob_start();
        echo <<<PHP
<?php

namespace {$namespace}\\Installer;

use CannyDain\Shorty\Modules\Interfaces\ModuleInstallerInterface;
use CannyDain\Lib\Forms\Models\InputField;

class {$this->_moduleName}Installer implements ModuleInstallerInterface
{
    public function getName() { return "{$this->_moduleName}"; }

    /**
     * @return InputField[]
     */
    public function getFields() { return array(); }

    /**
     * @param InputField[] \$fields
     * @return mixed
     */
    public function install(\$fields) {}
}
PHP;
        $installerContents = ob_get_clean();
        $exampleModelName = strtr($namespace.'\\Models\\MODEL_NAME', array('\\' => '\\\\'));

        ob_start();
        echo <<<JSON
[
    {
        "class":"\\\\$exampleModelName",
        "auto_id":"id",
        "id":
        [
            "id"
        ],
        "table":"TABLE_NAME",
        "fields":
        [
            {
                "column":"id",
                "type":"int",
                "property":"_id",
                "size":11
            }
        ]
    }
]
JSON;
        $datadictionaryContents = ob_get_clean();

        ob_start();
        echo <<<PHP
<?php

namespace $namespace\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Shorty\Consumers\DataMapperConsumer;

class $dataAccessClassname implements DataMapperConsumer
{
    /**
     * @var DataMapper
     */
    protected \$_datamapper;

    public function registerObjects()
    {
        \$file = dirname(dirname(__FILE__)).'/datadictionary/objects.json';
        \$builder = new JSONFileDefinitionBuilder();
        \$builder->readFile(\$file, \$this->_datamapper);
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper \$dependency)
    {
        \$this->_datamapper = \$dependency;
    }
}
PHP;
        $dataAccessContents = ob_get_clean();

        return array
        (
            strtolower($this->_moduleName).'module.php' => $moduleFileContents,
            'installer/'.strtolower($this->_moduleName).'installer.php' => $installerContents,
            'datadictionary/objects.json' => $datadictionaryContents,
            'dataaccess/'.strtolower($this->_moduleName).'dataaccess.php' => $dataAccessContents,
        );
    }

    public function dependenciesConsumed()
    {
        // moduleInfoFile
        // installer
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }
}

ShortyInit::main(new ModuleCreatorMain());