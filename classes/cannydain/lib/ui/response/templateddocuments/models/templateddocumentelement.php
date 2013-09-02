<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models;

use CannyDain\Lib\UI\Views\ViewInterface;

abstract class TemplatedDocumentElement
{
    public abstract function display(ViewInterface $view);
}
