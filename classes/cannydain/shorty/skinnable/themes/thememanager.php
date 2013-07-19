<?php

namespace CannyDain\Shorty\Skinnable\Themes;

use CannyDain\Lib\UI\Response\Response;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\ResponseConsumer;
use CannyDain\Shorty\Skinnable\Themes\Models\Theme;

class ThemeManager implements RequestConsumer, ResponseConsumer
{
    protected $_themes = array();
    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var Response
     */
    protected $_response;

    protected $_themeID = null;

    public function addTheme(Theme $theme)
    {
        $this->_themes[$theme->getId()] = $theme;
    }

    public function getCurrentThemeID()
    {
        if ($this->_themeID == null)
        {
            $this->_themeID = $this->_request->getCookie('theme');
            if ($this->_themeID == '')
                $this->_themeID = 0;
        }

        return $this->_themeID;
    }

    public function setCurrentThemeID($newTheme)
    {
        $this->_themeID = $newTheme;
        $this->_response->setCookie('theme', $newTheme);
    }

    public function getCurrentTheme()
    {
        return $this->getThemeByIDOrDefaultTheme($this->getCurrentThemeID());
    }

    public function getThemeByIDOrDefaultTheme($id = null)
    {
        if (isset($this->_themes[$id]))
            return $this->_themes[$id];

        return $this->getDefaultTheme();
    }

    /**
     * @return Theme[]
     */
    public function getAllThemes()
    {
        return $this->_themes;
    }

    /**
     * @return Theme
     */
    public function getDefaultTheme()
    {
        return $this->_themes[0];
    }

    private function __construct() {}
    private function __clone() {}

    public static function Singleton()
    {
        static $self = null;

        if ($self == null)
            $self = new ThemeManager();

        return $self;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }

    public function consumeResponse(Response $dependency)
    {
        $this->_response = $dependency;
    }
}