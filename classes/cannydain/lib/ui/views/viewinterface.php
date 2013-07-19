<?php

namespace CannyDain\Lib\UI\Views;

interface ViewInterface
{
    const CONTENT_TYPE_NONE = 'none';
    const CONTENT_TYPE_HTML = 'text/html';
    const CONTENT_TYPE_JSON = 'application/json';

    public function display();
    public function getContentType();
}