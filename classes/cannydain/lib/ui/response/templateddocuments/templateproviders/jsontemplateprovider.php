<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\TemplateProviders;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Exceptions\ElementNotFoundException;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Exceptions\TemplateNotFoundException;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Exceptions\TemplateParseException;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Template;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplateDocumentElementContainerInterface;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElementContainer;

class JSONTemplateProvider implements TemplateProviderInterface
{
    protected $_inputFile = '';

    public function __construct($inputFile = '')
    {
        $this->_inputFile = $inputFile;
    }

    /**
     *
     * @throws \CannyDain\Lib\UI\Response\TemplatedDocuments\Exceptions\TemplateParseException
     * @throws \CannyDain\Lib\UI\Response\TemplatedDocuments\Exceptions\TemplateNotFoundException
     * @return Template
     */
    public function getTemplate()
    {
        if (!file_exists($this->_inputFile))
            throw new TemplateNotFoundException($this->_inputFile);

        $data = json_decode(file_get_contents($this->_inputFile), true);
        if ($data == null)
            throw new TemplateParseException(json_last_error(), $this->_inputFile);

        $template = new Template();
        $template->setTemplateNodes($this->_parseElementArray($data));

        return $template;
    }

    /**
     * @param $array
     * @return TemplatedDocumentElement[]
     */
    protected function _parseElementArray($array)
    {
        $ret = array();

        foreach ($array as $data)
        {
            $element = $this->_elementFactory($data['class'], $data['params']);
            if ($element instanceof TemplateDocumentElementContainerInterface && isset($data['children']))
            {
                foreach ($this->_parseElementArray($data['children']) as $child)
                    $element->addChild($child);
            }

            $ret[] = $element;
        }

        return $ret;
    }

    /**
     * @param $classname
     * @param array $constructorParams
     * @return TemplatedDocumentElement
     * @throws \CannyDain\Lib\UI\Response\TemplatedDocuments\Exceptions\ElementNotFoundException
     */
    protected function _elementFactory($classname, $constructorParams = array())
    {
        if (!class_exists($classname))
            throw new ElementNotFoundException;

        $reflectionClass = new \ReflectionClass($classname);
        if(!$reflectionClass->isSubclassOf('\\CannyDain\\Lib\\UI\\Response\\TemplatedDocuments\\Models\\TemplatedDocumentElement'))
            throw new ElementNotFoundException;

        if (!$reflectionClass->isInstantiable())
            throw new ElementNotFoundException;

        return $reflectionClass->newInstanceArgs($constructorParams);
    }

    public function setInputFile($inputFile)
    {
        $this->_inputFile = $inputFile;
    }

    public function getInputFile()
    {
        return $this->_inputFile;
    }
}