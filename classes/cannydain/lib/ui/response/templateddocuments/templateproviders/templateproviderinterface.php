<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\TemplateProviders;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Template;

interface TemplateProviderInterface
{
    /**
     * @return Template
     */
    public function getTemplate();
}