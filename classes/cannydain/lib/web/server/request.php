<?php

namespace CannyDain\Lib\Web\Server;

use CannyDain\Lib\Web\Server\Models\FileUploadModel;

class Request
{
    protected $_parameters = array();
    protected $_cookies = array();
    protected $_requestedResource = '';
    protected $_requestMethod = '';
    /**
     * @var FileUploadModel[]
     */
    protected $_files = array();

    public function getResource()
    {
        return $this->_requestedResource;
    }

    /**
     * @param $fieldname
     * @return FileUploadModel[]
     */
    public function getFilesByFieldname($fieldname)
    {
        $ret = array();

        foreach ($this->_files as $file)
        {
            if ($file->getFieldname() == $fieldname)
                $ret[] = $file;
        }

        return $ret;
    }

    public function getParameters()
    {
        return $this->_parameters;
    }

    public function isPost()
    {
        return $this->_requestMethod == 'POST';
    }

    public function loadFromHTTPRequest($resourceParameter = 'r')
    {
        $this->_requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->_cookies = $_COOKIE;
        if (isset($_GET[$resourceParameter]))
        {
            $this->_requestedResource = $_GET[$resourceParameter];
            unset($_GET[$resourceParameter]);
        }

        switch($this->_requestMethod)
        {
            case 'POST':
                $this->_parameters = $_POST;
                break;
            default:
                $this->_parameters = $_GET;
                break;
        }

        foreach ($_FILES as $fieldname => $data)
        {
            if (is_array($data['name']))
            {
                foreach ($data['name'] as $index => $name)
                {
                    $file = new FileUploadModel();
                    $file->setFieldname($name);
                    $file->setFieldname($fieldname);
                    $file->setIndex($index);
                    $file->setMimeType($data['type'][$index]);
                    $file->setTmpFile($data['tmp_name'][$index]);
                    $file->setSize($data['size'][$index]);

                    $this->_files[] = $file;
                }
            }
            else
            {
                $file = new FileUploadModel();
                $file->setFieldname($fieldname);
                $file->setFilename($data['name']);
                $file->setMimeType($data['type']);
                $file->setTmpFile($data['tmp_name']);
                $file->setSize($data['size']);

                $this->_files[] = $file;
                //$file->setFilename($data['error']);
            }
        }
    }

    public function getCookie($cookie)
    {
        if (!isset($this->_cookies[$cookie]))
            return null;

        return $this->_cookies[$cookie];
    }

    public function getParameter($param)
    {
        if (!isset($this->_parameters[$param]))
            return null;

        return $this->_parameters[$param];
    }
}