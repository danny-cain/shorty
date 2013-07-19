<?php

namespace CannyDain\Shorty\Skinnable\Themes\TemplateElements;

use CannyDain\Lib\UI\Response\Response;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML\StylesheetElement;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Consumers\ResponseConsumer;
use CannyDain\Shorty\Skinnable\Themes\ThemeManager;

class ThemeStylesElement extends TemplatedDocumentElement implements RequestConsumer, ResponseConsumer
{
    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var Response
     */
    protected $_response;

    protected $_id = 0;

    public function display(ViewInterface $view)
    {
        foreach (ThemeManager::Singleton()->getThemeByIDOrDefaultTheme($this->_id)->getStylesheets() as $stylesheet)
        {
            $element = new StylesheetElement($stylesheet);
            $element->display($view);
        }
    }

    public function dependenciesConsumed()
    {
        if ($this->_request->getParameter('skinnableAction') == 'changeTheme')
        {
            ThemeManager::Singleton()->setCurrentThemeID($this->_request->getParameter('theme'));
        }
        $this->_id = ThemeManager::Singleton()->getCurrentThemeID();
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