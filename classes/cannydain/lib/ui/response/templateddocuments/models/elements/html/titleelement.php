<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\TitledView;
use CannyDain\Lib\UI\Views\ViewInterface;

class TitleElement extends TemplatedDocumentElement
{
    protected $_siteTitle;

    public function __construct($siteTitle)
    {
        $this->_siteTitle = $siteTitle;
    }

    public function display(ViewInterface $view)
    {
        $title = $this->_siteTitle;
        if ($view instanceof TitledView)
            $title .= ' - '.$view->getTitle();

        echo '<title>'.$title.'</title>';
    }
}