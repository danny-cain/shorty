<?php

namespace CannyDain\Lib\Forms\Models;

class MultipleInputField extends InputField
{
    protected $_options = array();

    const TYPE_SINGLE_SELECT = 'single';
    const TYPE_MULTI_SELECT = 'multi';

    public function __construct($name = '', $caption = '', $type = '', $value = null, $options = array())
    {
        parent::__construct($name, $caption, $type, $value);
        $this->_options = $options;
    }


    public function setOptions($options)
    {
        $this->_options = $options;
    }

    public function getOptions()
    {
        return $this->_options;
    }
}