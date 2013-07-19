<?php

namespace CannyDain\ShortyCoreModules\TemplateManager\Controllers;

use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\RedirectView;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Consumers\ConfigurationConsumer;
use CannyDain\Shorty\Controllers\ShortyController;
use CannyDain\ShortyCoreModules\TemplateManager\Views\EditTemplateView;
use CannyDain\ShortyCoreModules\TemplateManager\Views\ListTemplatesView;

class TemplateEditorController extends ShortyController implements ConfigurationConsumer
{
    const CONTROLLER_CLASS_NAME = __CLASS__;

    /**
     * @var ShortyConfiguration
     */
    protected $_config;

    public function Index()
    {
        return $this->_view_ListTemplates();
    }

    public function Edit($file)
    {
        $templatesPath  = $this->_config->getValue(ShortyConfiguration::CONFIG_KEY_TEMPLATES_ROOT);
        $file = strtr($file, array('/' => '', '\\' => '', '..' => ''));

        if (!file_exists($templatesPath.$file.'.json'))
            return new RedirectView($this->_router->getURI(new Route(__CLASS__)));

        $template = file_get_contents($templatesPath.$file.'.json');

        $view = $this->_view_EditView($template, $file, true);

        if ($this->_request->isPost())
        {
            copy($templatesPath.$file.'.json', $templatesPath.$file.'.json.bak');
            unlink($templatesPath.$file.'.json');

            $view->updateFromRequest($this->_request);
            $file = strtr($view->getTemplateName(), array('/' => '', '\\' => '', '..' => ''));
            $file = $templatesPath.$file.'.json';

            if (!file_exists($file))
            {
                file_put_contents($file, $view->getTemplate());
                return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
            }
        }

        return $view;
    }

    public function Create()
    {
        $templatesPath  = $this->_config->getValue(ShortyConfiguration::CONFIG_KEY_TEMPLATES_ROOT);

        $view = $this->_view_EditView($this->_getBlankTemplate(), 'untitled', false);

        if ($this->_request->isPost())
        {
            $view->updateFromRequest($this->_request);
            $file = strtr($view->getTemplateName(), array('/' => '', '\\' => '', '..' => ''));
            $file = $templatesPath.$file.'.json';

            if (!file_exists($file))
            {
                file_put_contents($file, $view->getTemplate());
                return new RedirectView($this->_router->getURI(new Route(__CLASS__)));
            }
        }
        return $view;
    }

    protected function _view_EditView($template, $templateName, $isEdit = true)
    {
        $view = new EditTemplateView();

        $this->_dependencies->applyDependencies($view);

        $view->setTemplate($template);
        $view->setTemplateName($templateName);
        if ($isEdit)
            $view->setSaveRoute(new Route(__CLASS__, 'Edit', array($templateName)));
        else
            $view->setSaveRoute(new Route(__CLASS__, 'Create'));

        return $view;
    }

    protected function _view_ListTemplates()
    {
        $templates = array();

        $templatesPath  = $this->_config->getValue(ShortyConfiguration::CONFIG_KEY_TEMPLATES_ROOT);
        $dir = opendir($templatesPath);
        while ($file = readdir($dir))
        {
            $fullPath = $templatesPath.$file;
            if (is_dir($fullPath))
                continue;

            $nameParts = explode('.', $file);
            if (strtolower(array_pop($nameParts)) != 'json')
                continue;

            $templates[] = implode('.', $nameParts);
        }

        $view = new ListTemplatesView();
        $this->_dependencies->applyDependencies($view);
        $view->setEditRoute(new Route(__CLASS__, 'Edit', array('#name#')));
        $view->setTemplateNames($templates);
        $view->setCreateRoute(new Route(__CLASS__, 'Create'));

        return $view;
    }

    protected function _getBlankTemplate()
    {
        return <<<JSON
[
    {
        "class": "\\\\CannyDain\\\\Lib\\\\UI\\\\Response\\\\TemplatedDocuments\\\\Models\\\\Elements\\\\HTML\\\\HeadElement",
        "params": [],
        "children": [
            {
                "class": "\\\\CannyDain\\\\Lib\\\\UI\\\\Response\\\\TemplatedDocuments\\\\Models\\\\Elements\\\\HTML\\\\TitleElement",
                "params": ["Shorty CMS"],
                "children": []
            },
            {
                "class": "\\\\CannyDain\\\\Shorty\\\\Skinnable\\\\Themes\\\\TemplateElements\\\\ThemeStylesElement",
                "params": [],
                "children": []
            },
            {
                "class": "\\\\CannyDain\\\\Lib\\\\UI\\\\Response\\\\TemplatedDocuments\\\\Models\\\\Elements\\\\HTML\\\\ScriptElement",
                "params": ["/scripts/jquery.min.js"],
                "children": []
            }
        ]
    },
    {
        "class": "\\\\CannyDain\\\\Lib\\\\UI\\\\Response\\\\TemplatedDocuments\\\\Models\\\\Elements\\\\HTML\\\\BodyElement",
        "params": [],
        "children": [
            {
                "class": "\\\\CannyDain\\\\Lib\\\\UI\\\\Response\\\\TemplatedDocuments\\\\Models\\\\Elements\\\\HTML\\\\BlockLevelElement",
                "params": ["", ["contentPane"]],
                "children": [
                    {
                        "class": "\\\\CannyDain\\\\Lib\\\\UI\\\\Response\\\\TemplatedDocuments\\\\Models\\\\Elements\\\\HTML\\\\BlockLevelElement",
                        "params": ["content"],
                        "children": [
                            {
                                "class": "\\\\CannyDain\\\\Lib\\\\UI\\\\Response\\\\TemplatedDocuments\\\\Models\\\\Elements\\\\ContentElement",
                                "params": [],
                                "children": []
                            }
                        ]
                    }
                ]
            },
            {
                "class": "\\\\CannyDain\\\\Lib\\\\UI\\\\Response\\\\TemplatedDocuments\\\\Models\\\\Elements\\\\HTML\\\\BlockLevelElement",
                "params": ["footer"],
                "children": []
            }
        ]
    }
]
JSON;

    }

    public function __isAdministratorOnly()
    {
        return true;
    }


    public function consumeConfiguration(ShortyConfiguration $dependency)
    {
        $this->_config = $dependency;
    }
}