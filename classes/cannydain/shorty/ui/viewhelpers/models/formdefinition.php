<?php

namespace CannyDain\Shorty\UI\ViewHelpers\Models;

use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\UI\ViewHelpers\Models\Fields\Checkbox;
use CannyDain\Shorty\UI\ViewHelpers\Models\Fields\Date;
use CannyDain\Shorty\UI\ViewHelpers\Models\Fields\LargeText;
use CannyDain\Shorty\UI\ViewHelpers\Models\Fields\MultiSelectBox;
use CannyDain\Shorty\UI\ViewHelpers\Models\Fields\Password;
use CannyDain\Shorty\UI\ViewHelpers\Models\Fields\RichText;
use CannyDain\Shorty\UI\ViewHelpers\Models\Fields\SelectBox;
use CannyDain\Shorty\UI\ViewHelpers\Models\Fields\Textbox;

class FormDefinition
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /**
     * @var FieldDefinition[]
     */
    protected $_fields = array();
    protected $_method = self::METHOD_POST;
    protected $_uri = '';
    protected $_submitCaption = 'Submit';

    public function display()
    {
        echo '<form method="'.$this->_method.'" action="'.$this->_uri.'">';
            foreach ($this->_fields as $field)
            {
                $classes = array('formFieldRow');
                if ($field->getErrorMessage() != '')
                    $classes[] = 'formError';

                echo '<div class="'.implode(' ', $classes).'">';
                    echo '<div class="formCaption">';
                        echo $field->getCaption();
                    echo '</div>';

                    echo '<div class="formInput">';
                        echo $field->getFieldMarkup();
                    echo '</div>';

                    echo '<div class="formHelp">';
                        if ($field->getErrorMessage() != '')
                            echo $field->getErrorMessage();
                        else
                            echo $field->getHelpText();
                    echo '</div>';
                echo '</div>';
            }

            echo '<div class="formFieldRow">';
                echo '<div class="formCaption">';
                echo '</div>';

                echo '<div class="formInput">';
                echo '</div>';

                echo '<div class="formHelp">';
                    echo '<input type="submit" value="'.$this->_submitCaption.'" />';
                echo '</div>';
            echo '</div>';
        echo '</form>';
    }

    public function updateFromRequest(Request $request)
    {
        foreach ($this->_fields as $field)
            $field->updateFromRequest($request);
    }

    public function getFields()
    {
        return $this->_fields;
    }

    public function checkbox($name, $caption, $value, $checked = false, $helpText = '', $errors = '')
    {
        $field = new Checkbox();
        $field->setCaption($caption);
        $field->setFieldName($name);
        $field->setValue($value);
        $field->setHelpText($helpText);
        $field->setErrorMessage($errors);
        $field->setChecked($checked);

        $this->_addField($name, $field);

        return $this;
    }

    public function date($name, $caption, $value, $startYear, $endYear, $helpText = '', $errors = '')
    {
        $field = new Date();
        $field->setCaption($caption);
        $field->setFieldName($name);
        $field->setValue($value);
        $field->setHelpText($helpText);
        $field->setErrorMessage($errors);
        $field->setStartYear($startYear);
        $field->setEndYear($endYear);

        $this->_addField($name, $field);

        return $this;
    }

    public function largetext($name, $caption, $value, $helpText = '', $errors = '')
    {
        $field = new LargeText();
        $field->setCaption($caption);
        $field->setFieldName($name);
        $field->setValue($value);
        $field->setHelpText($helpText);
        $field->setErrorMessage($errors);

        $this->_addField($name, $field);

        return $this;
    }

    public function password($name, $caption, $value, $helpText = '', $errors = '')
    {
        $field = new Password();
        $field->setCaption($caption);
        $field->setFieldName($name);
        $field->setValue($value);
        $field->setHelpText($helpText);
        $field->setErrorMessage($errors);

        $this->_addField($name, $field);

        return $this;
    }

    public function richtext($name, $caption, $value, $helpText = '', $errors = '')
    {
        $field = new RichText();
        $field->setCaption($caption);
        $field->setFieldName($name);
        $field->setValue($value);
        $field->setHelpText($helpText);
        $field->setErrorMessage($errors);

        $this->_addField($name, $field);

        return $this;
    }

    public function multiselect($name, $caption, $value, $options, $helpText = '', $errors = '')
    {
        $field = new MultiSelectBox();
        $field->setCaption($caption);
        $field->setFieldName($name);
        $field->setValue($value);
        $field->setHelpText($helpText);
        $field->setErrorMessage($errors);
        $field->setOptions($options);

        $this->_addField($name, $field);

        return $this;
    }

    public function select($name, $caption, $value, $options, $helpText = '', $errors = '')
    {
        $field = new SelectBox();
        $field->setCaption($caption);
        $field->setFieldName($name);
        $field->setValue($value);
        $field->setHelpText($helpText);
        $field->setErrorMessage($errors);
        $field->setOptions($options);

        $this->_addField($name, $field);

        return $this;
    }

    public function textbox($name, $caption, $value, $helpText = '', $errors = '')
    {
        $field = new Textbox();
        $field->setCaption($caption);
        $field->setFieldName($name);
        $field->setValue($value);
        $field->setHelpText($helpText);
        $field->setErrorMessage($errors);

        $this->_addField($name, $field);

        return $this;
    }

    /**
     * @param string $fieldname
     * @param FieldDefinition $fieldObject
     */
    protected function _addField($fieldname, $fieldObject)
    {
        $this->_fields[$fieldname] = $fieldObject;
    }
}