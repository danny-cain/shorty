<?php

namespace CannyDain\Shorty\UI\ViewHelpers\Models\Fields;

use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\UI\ViewHelpers\Models\FieldDefinition;

class Date extends FieldDefinition
{
    protected $_startYear = 0;
    protected $_endYear = 0;

    public function getFieldMarkup()
    {
        $day = date('j', $this->_value);
        $month = date('n', $this->_value);
        $year = date('Y', $this->_value);

        ob_start();

            echo '<select name="'.$this->_fieldName.'[day]">';
            for($i = 1; $i <= 31; $i ++)
            {
                $twoDigitDay = $i;
                if (strlen($twoDigitDay) == 1)
                    $twoDigitDay = '0'.$twoDigitDay;

                $sampleDate = strtotime('2000-01-'.$twoDigitDay);
                $ordinal = date('S', $sampleDate);
                $selected = '';
                if ($i == $day)
                    $selected = ' selected="selected"';

                echo '<option value="'.$twoDigitDay.'"'.$selected.'>'.$i.'<sup>'.$ordinal.'</sup></option>';
            }
            echo '</select>';

            echo '<select name="'.$this->_fieldName.'[month]">';
            for($i = 1; $i <= 12; $i ++)
            {
                $twoDigitMonth = $i;
                if (strlen($twoDigitMonth) == 1)
                    $twoDigitMonth = '0'.$twoDigitMonth;

                $sampleDate = strtotime('2000-01-'.$twoDigitMonth);
                $monthName = date('F', $sampleDate);
                $selected = '';
                if ($i == $month)
                    $selected = ' selected="selected"';

                echo '<option value="'.$twoDigitMonth.'"'.$selected.'>'.$monthName.'</option>';
            }
            echo '</select>';

            echo '<select name="'.$this->_fieldName.'[year]">';
            for ($i = $this->_startYear; $i <= $this->_endYear; $i ++)
            {
                $selected = '';
                if ($i == $year)
                    $selected = ' selected="selected"';

                echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
            }
            echo '</select>';
        return ob_get_clean();
    }

    public function updateFromRequest(Request $request)
    {
        $date = $request->getParameter($this->_fieldName);
        $strDate = $date['year'].'-'.$date['month'].'-'.$date['day'];

        return strtotime($strDate);
    }

    public function setEndYear($endYear)
    {
        $this->_endYear = $endYear;
    }

    public function getEndYear()
    {
        return $this->_endYear;
    }

    public function setStartYear($startYear)
    {
        $this->_startYear = $startYear;
    }

    public function getStartYear()
    {
        return $this->_startYear;
    }
}