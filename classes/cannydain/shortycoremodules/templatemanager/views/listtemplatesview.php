<?php

namespace CannyDain\ShortyCoreModules\TemplateManager\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Shorty\Consumers\RouterConsumer;

class ListTemplatesView extends HTMLView implements RouterConsumer
{
    protected $_templateNames = array();

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var Route
     */
    protected $_editRoute;

    /**
     * @var Route
     */
    protected $_createRoute;

    public function display()
    {
        echo '<h1>Choose a template to edit</h1>';

        foreach ($this->_templateNames as $template)
            $this->_displayTemplate($template);

        if ($this->_createRoute != null)
        {
            $createURI = $this->_router->getURI($this->_createRoute);
            echo '<div>';
                echo '<a href="'.$createURI.'">Create New Template</a>';
            echo '</div>';
        }
    }

    protected function _displayTemplate($name)
    {
        $uri = $this->_router->getURI($this->_editRoute->getRouteWithReplacements(array('#name#' => $name)));

        echo '<div>';
            echo '<a href="'.$uri.'">'.$name.'</a>';
        echo '</div>';
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $createRoute
     */
    public function setCreateRoute($createRoute)
    {
        $this->_createRoute = $createRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getCreateRoute()
    {
        return $this->_createRoute;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $editRoute
     */
    public function setEditRoute($editRoute)
    {
        $this->_editRoute = $editRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getEditRoute()
    {
        return $this->_editRoute;
    }

    public function setTemplateNames($templateNames)
    {
        $this->_templateNames = $templateNames;
    }

    public function getTemplateNames()
    {
        return $this->_templateNames;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}