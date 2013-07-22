<?php

namespace CannyDain\Shorty\UI\ViewHelpers;

class FormHelper
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    public function startForm($action, $method = 'POST', $classes = array())
    {
        echo '<form method="'.$method.'" action="'.$action.'" class="'.implode(' ', $classes).'">';
    }

    public function submitButton($caption)
    {
        $markup = '<input type="submit" value="'.$caption.'" />';
        $this->_writeField('', '', $markup);
    }

    public function multiSelect($name, $caption, $options = array(), $values = array(), $helpText = '')
    {
        static $scriptOutput = false;

        if (!$scriptOutput)
        {
            $scriptOutput = true;
            echo <<<HTML
<script type="text/javascript">
    function toggleCheckbox(element)
    {
        if (element.is(':checked'))
            element.removeAttr('checked')
        else
            element.attr('checked', 'checked');
    }
</script>
HTML;

        }
        if (!is_array($values))
            $values = array();

        ob_start();

            foreach ($options as $val => $label)
            {
                $selected = '';
                if (in_array($val, $values))
                    $selected = ' checked="checked"';

                echo '<div style="display: inline-block; width: 150px; height: 25px; vertical-align: top; margin: 5px;">';
                    echo '<input type="checkbox" name="'.$name.'[]" value="'.$val.'"'.$selected.' /><label onclick="toggleCheckbox($(this).prev()); return false; ">'.$label.'</label>';
                echo '</div>';
            }

        $markup = ob_get_clean();

        $this->_writeField($markup, $caption, $helpText);
    }

    public function select($name, $caption, $options = array(), $selectedValue = null, $helpText = '')
    {
        ob_start();
        echo '<select name="'.$name.'">';
            foreach ($options as $val => $label)
            {
                $selected = '';
                if ($val == $selectedValue)
                    $selected = ' selected="selected"';

                echo '<option value="'.$val.'"'.$selected.'>'.$label.'</option>';
            }
        echo '</select>';
        $markup = ob_get_clean();

        $this->_writeField($markup, $caption, $helpText);
    }

    public function editCheckbox($name, $caption, $value, $isChecked, $helpText = '')
    {
        $checked = '';
        if ($isChecked)
            $checked = ' checked="checked"';

        $markup = '<input name="'.$name.'" type="checkbox" value="'.$value.'"'.$checked.' />';
        $this->_writeField($markup, $caption, $helpText);
    }

    public function editRichText($name, $caption, $value, $helpText = '')
    {
        $markup = '<textarea class="richtext" name="'.$name.'">'.htmlentities($value, ENT_COMPAT, 'UTF-8').'</textarea>';
        $this->_writeField($markup, $caption, $helpText);
    }

    public function editLargeText($name, $caption, $value, $helpText = '')
    {
        $markup = '<textarea name="'.$name.'">'.htmlentities($value, ENT_COMPAT, 'UTF-8').'</textarea>';
        $this->_writeField($markup, $caption, $helpText);
    }

    public function editPassword($name, $caption, $helpText = '')
    {
        $markup = '<input type="password" name="'.$name.'" />';
        $this->_writeField($markup, $caption, $helpText);
    }

    public function hiddenField($name, $value)
    {
        echo '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
    }

    public function editTextWithoutAutocomplete($name, $caption, $value, $helpText = '')
    {
        $markup = '<input type="text" autocomplete="off" name="'.$name.'" value="'.htmlentities($value, ENT_QUOTES, 'UTF-8').'" />';
        $this->_writeField($markup, $caption, $helpText);
    }

    public function editText($name, $caption, $value, $helpText = '')
    {
        $markup = '<input type="text" name="'.$name.'" value="'.htmlentities($value, ENT_QUOTES, 'UTF-8').'" />';
        $this->_writeField($markup, $caption, $helpText);
    }

    public function antiBotHiddenField($name)
    {
        echo '<input type="text" style="height: 0; width: 0; visibility: hidden;" name="'.$name.'" />';
    }

    public function editDate($name, $caption, $value, $minDate, $maxDate, $helpText = '')
    {
        $day = date('j', $value);
        $month = date('n', $value);
        $year = date('Y', $value);

        $minYear = date('Y', $minDate);
        $maxYear = date('Y', $maxDate);

        ob_start();
            echo '<div class="dateSelect">';
                echo '<select name="'.$name.'[day]">';
                for ($i = 1; $i <= 31; $i ++)
                {
                    $suffix = date('S', strtotime('2013-01-'.$i));

                    $selected = '';
                    if ($i == $day)
                        $selected = ' selected="selected"';

                    echo '<option value="'.$i.'"'.$selected.'>'.$i.$suffix.'</option>';
                }
                echo '</select>';

                echo '<select name="'.$name.'[month]">';
                for ($i = 1; $i <= 12; $i ++)
                {
                    $monthName = date('F', strtotime('2013-'.$i.'-01'));

                    $selected = '';
                    if ($i == $month)
                        $selected = ' selected="selected"';

                    echo '<option value="'.$i.'"'.$selected.'>'.$monthName.'</option>';
                }
                echo '</select>';

                echo '<select name="'.$name.'[year]">';
                for ($i = $minYear; $i <= $maxYear; $i ++)
                {
                    $selected = '';
                    if ($i == $year)
                        $selected = ' selected="selected"';

                    echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
                }
                echo '</select>';
            echo '</div>';

        $this->_writeField(ob_get_clean(), $caption, $helpText);
    }

    public function editDateTime($name, $caption, $value, $minDate, $maxDate, $helpText = '')
    {
        $day = date('j', $value);
        $month = date('n', $value);
        $year = date('Y', $value);

        $hour = date('H', $value);
        $minute = date('i', $value);

        $minYear = date('Y', $minDate);
        $maxYear = date('Y', $maxDate);

        ob_start();
            echo '<div class="dateSelect">';
                echo '<select name="'.$name.'[day]">';
                for ($i = 1; $i <= 31; $i ++)
                {
                    $suffix = date('S', strtotime('2013-01-'.$i));

                    $selected = '';
                    if ($i == $day)
                        $selected = ' selected="selected"';

                    echo '<option value="'.$i.'"'.$selected.'>'.$i.$suffix.'</option>';
                }
                echo '</select>';

                echo '<select name="'.$name.'[month]">';
                for ($i = 1; $i <= 12; $i ++)
                {
                    $monthName = date('F', strtotime('2013-'.$i.'-01'));

                    $selected = '';
                    if ($i == $month)
                        $selected = ' selected="selected"';

                    echo '<option value="'.$i.'"'.$selected.'>'.$monthName.'</option>';
                }
                echo '</select>';

                echo '<select name="'.$name.'[year]">';
                for ($i = $minYear; $i <= $maxYear; $i ++)
                {
                    $selected = '';
                    if ($i == $year)
                        $selected = ' selected="selected"';

                    echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
                }
                echo '</select>';

                echo '<input type="text" name="'.$name.'[hour]" value="'.$hour.'">';
                echo '<input type="text" name="'.$name.'[minute]" value="'.$minute.'">';
            echo '</div>';

        $this->_writeField(ob_get_clean(), $caption, $helpText);
    }

    protected function _writeField($markup, $caption, $helpText = '')
    {
        echo '<div class="formFieldRow">';
            echo '<div class="formCaption">';
                echo $caption;
            echo '</div>';

            echo '<div class="formInput">';
                echo $markup;
            echo '</div>';

            echo '<div class="formHelp">';
                echo $helpText;
            echo '</div>';
        echo '</div>';
    }

    public function endForm()
    {
        echo '</form>';
    }
}