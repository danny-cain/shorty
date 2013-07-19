<?php

namespace CannyDain\Shorty\Config;

class ShortyConfiguration
{
    const CONFIG_KEY_CLASSES_ROOT = 'shorty.filesystem.classRoot';
    const CONFIG_KEY_FILE_ROOT = 'shorty.filesystem.root';
    const CONFIG_KEY_TEMPLATES_ROOT = 'shorty.filesystem.templatesRoot';

    const CONFIG_KEY_EMAILING_CONTACT_FORM_EMAIL = 'shorty.emailing.contactFormEmail';
    const CONFIG_KEY_EMAILING_COMMENTS_EMAIL = 'shorty.emailing.commentsFormEmail';

    const CONFIG_KEY_DATABASE_USER = 'shorty.database.user';
    const CONFIG_KEY_DATABASE_PASS = 'shorty.database.pass';
    const CONFIG_KEY_DATABASE_HOST = 'shorty.database.host';
    const CONFIG_KEY_DATABASE_NAME = 'shorty.database.name';
    const CONFIG_KEY_INSTALLATION_COMPLETE = 'shorty.installation.complete';

    protected $_configuration = array();
    protected $_filename = '';
    protected $_configExists = false;

    public function __construct($filename = '', $configReplacements = array())
    {
        $this->_filename = $filename;
        if (!file_exists($filename))
            return;

        $this->_configExists = true;
        $data = strtr(file_get_contents($filename), $configReplacements);
        $this->_configuration = json_decode($data, true);
        if (!is_array($this->_configuration))
            $this->_configuration = array();
    }

    public function writeConfig()
    {
        if (file_exists($this->_filename))
            unlink($this->_filename);

        $directoryName = dirname($this->_filename);
        if (!file_exists($directoryName))
            mkdir($directoryName, 0777, true);

        file_put_contents($this->_filename, json_encode($this->_configuration));
    }

    public function canInstall()
    {
        if (!$this->exists())
            return true;

        return $this->getValue(self::CONFIG_KEY_INSTALLATION_COMPLETE) != '1';
    }

    public function exists()
    {
        return $this->_configExists;
    }

    public function getRawConfig() { return $this->_configuration; }
    public function setRawConfig($config) { $this->_configuration = $config; }

    public function getValue($key)
    {
        return $this->_extractConfigPathFromConfigArray($this->_configuration, $key);
    }

    protected function _extractConfigPathFromConfigArray($array, $path)
    {
        $pathSegments = explode('.', $path);
        $segment = array_shift($pathSegments);

        if (!isset($array[$segment]))
            return null;

        if (count($pathSegments) == 0)
            return $array[$segment];

        return $this->_extractConfigPathFromConfigArray($array[$segment], implode('.', $pathSegments));
    }
}