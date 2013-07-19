<?php

namespace CannyDain\Shorty\UI\Response\Templated\Elements;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Consumers\NavigationConsumer;
use CannyDain\Shorty\Navigation\NavigationProvider;

class NavigationElement extends TemplatedDocumentElement implements NavigationConsumer
{
    /**
     * @var \CannyDain\ShortyCoreModules\ShortyNavigation\Providers\NavigationProvider
     */
    protected $_nav;

    public function display(ViewInterface $view)
    {
        $this->_nav->displayNavigation();
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeNavigationProvider(NavigationProvider $dependency)
    {
        $this->_nav = $dependency;
    }
}