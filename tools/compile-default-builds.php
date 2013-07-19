<?php

require dirname(dirname(__FILE__)).'/classes/cannydain/initialise.php';

    /**
     *@var BuildDefinition[] $builds
     */
    $builds = array
    (
        new BuildDefinition('Core', 'core.phar', array
        (
            'cannydain/lib/',
        ), array
        (
            'cannydain/autoloader.php',
            'cannydain/initialise.php',
        ), '<?php require dirname(__FILE__)."/cannydain/initialise.php";'),
        new BuildDefinition('Shorty', 'shorty.phar', array
        (
            'cannydain/shorty/',
        ), array
        (
        ), '<?php \CannyDain\Autoloader::Singleton()->registerRootPath(dirname(__FILE__)."/");'),
        new BuildDefinition('Modules', 'modules.phar', array
        (
            'cannydain/shortycoremodules/',
        ), array
        (

        ), '<?php \CannyDain\Autoloader::Singleton()->registerRootPath(dirname(__FILE__)."/");'),
    );

    $rootPath = dirname(dirname(__FILE__)).'/classes/';
    $buildPath = dirname(dirname(__FILE__)).'/build/';

    foreach ($builds as $build)
    {
        echo "Building ".$build->getBuildName()."\r\n";
        $phar = new \CannyDain\Lib\Archiving\PharBuilder();

        foreach ($build->getIncludedDirectories() as $dir)
            $phar->addDirectory($rootPath.$dir, $dir);

        foreach ($build->getIncludedFiles() as $file)
            $phar->addFile($rootPath.$file, $file);

        $phar->addFileAsString('index.php', $build->getStub());

        if (file_exists($buildPath.$build->getFilename()))
            unlink($buildPath.$build->getFilename());

        if (file_exists($buildPath.$build->getFilename().'.gz'))
            unlink($buildPath.$build->getFilename().'.gz');

        $phar->compile($buildPath.$build->getFilename());
        echo "Built to ".$buildPath.$build->getFilename()."\r\n";
    }

class BuildDefinition
{
    protected $_buildName = '';
    protected $_filename = '';
    protected $_includedDirectories = array();
    protected $_includedFiles = array();
    protected $_stub = '';

    function __construct($_buildName = '', $_filename = '', $_includedDirectories = array(), $_includedFiles = array(), $_stub = '')
    {
        $this->_buildName = $_buildName;
        $this->_includedDirectories = $_includedDirectories;
        $this->_filename = $_filename;
        $this->_includedFiles = $_includedFiles;
        $this->_stub = $_stub;
    }

    public function setIncludedFiles($includedFiles)
    {
        $this->_includedFiles = $includedFiles;
    }

    public function getIncludedFiles()
    {
        return $this->_includedFiles;
    }

    public function setStub($stub)
    {
        $this->_stub = $stub;
    }

    public function getStub()
    {
        return $this->_stub;
    }

    public function setFilename($filename)
    {
        $this->_filename = $filename;
    }

    public function getFilename()
    {
        return $this->_filename;
    }

    public function setBuildName($buildName)
    {
        $this->_buildName = $buildName;
    }

    public function getBuildName()
    {
        return $this->_buildName;
    }

    public function setIncludedDirectories($includedDirectories)
    {
        $this->_includedDirectories = $includedDirectories;
    }

    public function getIncludedDirectories()
    {
        return $this->_includedDirectories;
    }
}