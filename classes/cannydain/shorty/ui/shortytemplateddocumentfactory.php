<?php

namespace CannyDain\Shorty\UI;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\UI\Response\Document\DocumentInterface;
use CannyDain\Lib\UI\Response\Document\NullDocument;
use CannyDain\Lib\UI\Response\DocumentFactory;
use CannyDain\Lib\UI\Response\TemplatedDocuments\TemplatedDocument;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Config\ShortyConfiguration;
use CannyDain\Shorty\Consumers\ConfigurationConsumer;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Skinnable\Themes\ThemeManager;
use CannyDain\Shorty\UI\Response\ShortyHTMLDocument;
use CannyDain\Shorty\UI\Response\ShortyTemplatedHTMLDocument;
use CannyDain\Shorty\UI\Response\Templated\Providers\ShortyJSONTemplateProvider;

class ShortyTemplatedDocumentFactory implements DocumentFactory, DependencyConsumer, ConfigurationConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    protected $_basePath = '';

    /**
     * @var ShortyConfiguration
     */
    protected $_config;

    /**
     * @param ViewInterface $view
     * @return DocumentInterface
     */
    public function getDocumentForView(ViewInterface $view)
    {
        if ($view instanceof HTMLView)
        {
            if ($view->isPrintableView())
                return $this->_factory_PrintableHTMLView();
            else
                return $this->_factory_HTMLView();
        }

        if ($view->getContentType() == ViewInterface::CONTENT_TYPE_HTML)
            return $this->_factory_HTMLView();

        return $this->_factory_NullView();
    }

    protected function _factory_NullView()
    {
        $view = new NullDocument();
        $this->_dependencies->applyDependencies($view);

        return $view;
    }

    public function __construct()
    {

    }

    protected function _factory_PrintableHTMLView()
    {
        return new NullDocument;
    }

    protected function _factory_HTMLView()
    {
        $file = $this->_getTemplateFile();

        $doc = new ShortyTemplatedHTMLDocument();
        $templateProvider = new ShortyJSONTemplateProvider($file);
        $this->_dependencies->applyDependencies($templateProvider);

        $doc->setTemplate($templateProvider->getTemplate());
        $this->_dependencies->applyDependencies($doc);

        return $doc;
    }

    protected function _getTemplateFile()
    {
        $templatePath = $this->_basePath;
        $templateFile = ThemeManager::Singleton()->getCurrentTheme()->getTemplate();

        return $templatePath.$templateFile;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }

    public function consumeConfiguration(ShortyConfiguration $dependency)
    {
        $this->_config = $dependency;
        $this->_basePath = $dependency->getValue(ShortyConfiguration::CONFIG_KEY_TEMPLATES_ROOT);
    }
}