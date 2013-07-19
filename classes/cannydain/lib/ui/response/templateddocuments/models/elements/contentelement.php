<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\ViewInterface;

class ContentElement extends TemplatedDocumentElement
{
    public function display(ViewInterface $view)
    {
        $view->display();
    }
}