<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;

interface TemplateDocumentElementContainerInterface
{
    public function addChild(TemplatedDocumentElement $child);
}