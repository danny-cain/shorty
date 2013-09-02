<?php

namespace CannyDain\Shorty\Helpers\Forms\Models;

use CannyDain\Lib\Web\Server\Request;

class SubmitButton extends FieldModel
{
    public function __construct($caption = '')
    {
        $this->_caption = $caption;
    }

    public function getFieldMarkup()
    {
        return '<input type="submit" value="'.$this->_caption.'" />';
    }

    public function updateFromRequest(Request $request)
    {
        // TODO: Implement updateFromRequest() method.
    }
}