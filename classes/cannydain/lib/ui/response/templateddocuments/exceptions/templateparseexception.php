<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Exceptions;

class TemplateParseException extends TemplatedDocumentsException
{
    protected $_source;
    protected $_message;

    function __construct($_message, $_source)
    {
        $this->_message = $_message;
        $this->_source = $_source;
    }

    protected function _displayMessage()
    {
        echo '<p>Unable to parse template:<br>';
        echo '<pre>'.$this->_source.'</pre>';
        echo '</p>';

        echo '<p>'.$this->_message.'</p>';
    }


}