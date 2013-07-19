<?php

class Site
{
    protected $_localDomain = '';
    protected $_archiveNames = array();
    protected $_siteNamespace = '';
    protected $_pharName = '';

    function __construct($_siteNamespace, $_archiveNames, $_localDomain, $_sitePharName)
    {
        $this->_archiveNames = $_archiveNames;
        $this->_localDomain = $_localDomain;
        $this->_siteNamespace = $_siteNamespace;
        $this->_pharName = $_sitePharName;
    }

    public function setPharName($pharName)
    {
        $this->_pharName = $pharName;
    }

    public function getPharName()
    {
        return $this->_pharName;
    }

    public function setArchiveNames($archiveNames)
    {
        $this->_archiveNames = $archiveNames;
    }

    public function getArchiveNames()
    {
        return $this->_archiveNames;
    }

    public function setLocalDomain($localDomain)
    {
        $this->_localDomain = $localDomain;
    }

    public function getLocalDomain()
    {
        return $this->_localDomain;
    }

    public function setSiteNamespace($siteName)
    {
        $this->_siteNamespace = $siteName;
    }

    public function getSiteNamespace()
    {
        return $this->_siteNamespace;
    }
}

$sites = array
(
    'danny.dannycain.goblin' => new Site('DannyCain', array('core', 'shorty', 'modules'), 'danny.dannycain.goblin', 'site.phar'),
    'danny.shorty.goblin' => new Site(null, array('core', 'shorty', 'modules'), 'danny.shorty.goblin', null),
);

if (!isset($_SERVER['SERVER_NAME']) && isset($_SERVER['SHORTY_SITE']))
    $_SERVER['SERVER_NAME'] = $_SERVER['SHORTY_SITE'];
elseif (!isset($_SERVER['SERVER_NAME']))
{
    echo "Domain not set, please use set SHORTY_SITE=<localDomainName> before calling this script.\r\n";
    exit;
}

$siteName = $_SERVER['SERVER_NAME'];
if (!isset($sites[$siteName]))
{
    echo 'Site not found "'.$siteName.'"'."\r\n";
    exit;
}

require dirname(__FILE__).'/compile-default-builds.php';

/**
 * @var Site $site
 */
$site = $sites[$siteName];
// create phar of namespace (if not null) in /build/localsitename
// copy specified phar files from /build/* to /build/localsitename/*

$buildPath = dirname(dirname(__FILE__)).'/build/'.$site->getLocalDomain().'/';
if (!file_exists($buildPath))
    mkdir($buildPath, 0777, true);

if ($site->getSiteNamespace() != null)
{
    $filename = $site->getPharName();
    if (file_exists($buildPath.$filename))
        unlink ($buildPath.$filename);

    if (file_exists($buildPath.$filename.'.gz'))
        unlink($buildPath.$filename.'.gz');

    $phar = new \CannyDain\Lib\Archiving\PharBuilder();
    $phar->addDirectory(dirname(dirname(__FILE__)).'/classes/cannydain/sites/'.strtolower($site->getSiteNamespace()).'/', '/cannydain/sites/'.strtolower($site->getSiteNamespace()).'/');
    $phar->addFileAsString('index.php', '<?php \\CannyDain\\Autoloader::Singleton()->RegisterRootPath(dirname(__FILE__)."/"); ?>');
    $phar->compile($buildPath.$filename);
}

$sourcePath = dirname(dirname(__FILE__)).'/build/';
foreach ($site->getArchiveNames() as $archive)
{
    $source = $sourcePath.$archive.'.phar';
    $dest = $buildPath.$archive.'.phar';

    if (file_exists($dest))
        unlink($dest);

    if (file_exists($dest.'.gz'))
        unlink($dest.'.gz');

    copy($source, $dest);
    copy($source.'.gz', $dest.'.gz');
}