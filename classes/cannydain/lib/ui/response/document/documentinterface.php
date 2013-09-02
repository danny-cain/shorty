<?php

namespace CannyDain\Lib\UI\Response\Document;

use CannyDain\Lib\UI\Views\ViewInterface;

interface DocumentInterface
{
    public function display(ViewInterface $view);
}