<?php

namespace CannyDain\Shorty\Helpers\Forms;

use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Helpers\Forms\Models\FieldModel;
use CannyDain\Shorty\Helpers\Forms\Models\HiddenField;
use CannyDain\Shorty\Helpers\Forms\Models\SubmitButton;

class FormHelper implements FormHelperInterface
{
    /**
     * @var FieldModel[]
     */
    protected $_fields = array();

    protected $_uri = '';
    protected $_method = self::FORM_METHOD_GET;

    /**
     * @return FormHelperInterface
     */
    public function displayForm()
    {
        echo '<form method="'.$this->_method.'" action="'.$this->_uri.'">';
            foreach ($this->_fields as $field)
                $this->_writeField($field);
        echo '</form>';

        return $this;
    }

    protected function _writeHiddenField(HiddenField $field)
    {
        echo $field->getFieldMarkup();
    }

    protected function _writeSubmitField(SubmitButton $field)
    {
        echo '<div class="fieldRow submitRow">';
            echo $field->getFieldMarkup();
        echo '</div>';
    }

    protected function _writeField(FieldModel $field)
    {
        if ($field instanceof HiddenField)
        {
            $this->_writeHiddenField($field);
            return;
        }

        if ($field instanceof SubmitButton)
        {
            $this->_writeSubmitField($field);
            return;
        }

        $rowClasses = array('fieldRow');
        if ($field->getErrorText() != '')
            $rowClasses[] = 'errorRow';

        echo '<div class="'.implode(' ', $rowClasses).'">';
            echo '<div class="fieldCaption">';
                echo $field->getCaption();
            echo '</div>';

            echo '<div class="fieldInput">';
                echo $field->getFieldMarkup();
            echo '</div>';

            echo '<div class="fieldHelp">';
                if ($field->getErrorText() != '')
                    echo $field->getErrorText();
                else
                    echo $field->getHelpText();
            echo '</div>';
        echo '</div>';
    }

    /**
     * @param string $method
     * @return FormHelperInterface
     */
    public function setMethod($method = self::FORM_METHOD_GET)
    {
        $this->_method = $method;
        return $this;
    }

    /**
     * @param $uri
     * @return FormHelperInterface
     */
    public function setURI($uri)
    {
        $this->_uri = $uri;
        return $this;
    }

    /**
     * @param FieldModel $field
     * @return FormHelperInterface
     */
    public function addField(FieldModel $field)
    {
        if (isset($this->_fields[$field->getName()]))
            unset($this->_fields[$field->getName()]);

        $this->_fields[$field->getName()] = $field;

        return $this;
    }

    /**
     * @return FieldModel[]
     */
    public function getAllFields()
    {
        return $this->_fields;
    }

    /**
     * @param $fieldName
     * @return FieldModel
     */
    public function getField($fieldName)
    {
        if (!isset($this->_fields[$fieldName]))
            return null;

        return $this->_fields[$fieldName];
    }

    /**
     * @param Request $request
     * @return FormHelperInterface
     */
    public function updateFromRequest(Request $request)
    {
        foreach ($this->_fields as $field)
            $field->updateFromRequest($request);
    }
}