<?php

namespace CannyDain\Lib\UI\Views;

class JSONView implements ViewInterface
{
    protected $_data = null;

    function __construct($_data = null)
    {
        $this->_data = $_data;
    }


    public function display()
    {
        header("Content-Type: ".$this->getContentType());
        echo json_encode($this->_data);
    }

    public function setData($data)
    {
        $this->_data = $data;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function getContentType()
    {
        return self::CONTENT_TYPE_JSON;
    }
}