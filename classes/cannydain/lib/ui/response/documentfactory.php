<?php

namespace CannyDain\Lib\UI\Response;

use CannyDain\Lib\UI\Response\Document\DocumentInterface;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;

interface DocumentFactory
{
    /**
     * @param ViewInterface $view
     * @return DocumentInterface
     */
    public function getDocumentForView(ViewInterface $view);
}