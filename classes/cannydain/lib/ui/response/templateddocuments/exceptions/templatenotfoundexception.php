<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Exceptions;

class TemplateNotFoundException extends TemplatedDocumentsException
{
    protected $_templateFile = '';

    function __construct($_templateFile)
    {
        $this->_templateFile = $_templateFile;
    }

    protected function _displayMessage()
    {
        echo 'Template not found '.$this->_templateFile;
    }


}