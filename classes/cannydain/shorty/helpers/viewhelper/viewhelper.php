<?php

namespace CannyDain\Shorty\Helpers\ViewHelper;

use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Helpers\Forms\FormHelper;
use CannyDain\Shorty\Helpers\Forms\FormHelperInterface;
use CannyDain\Shorty\Helpers\ViewHelper\Models\ActionButtonModel;

class ViewHelper
{
    /**
     * @param ActionButtonModel[] $options
     */
    public function displayPageActions($options = array())
    {
        echo '<div class="pageActions">';
            foreach ($options as $option)
                $this->_displayButton($option);
        echo '</div>';
    }

    public function displayItemActions($options = array())
    {
        echo '<div class="itemActions">';
            foreach ($options as $option)
                $this->_displayButton($option);
        echo '</div>';
    }

    protected function _displayButton(ActionButtonModel $button)
    {
        if ($button->getUri() == '')
            return;

        $confirm = '';
        if ($button->getConfirmationMessage() != '')
            $confirm = ' onclick=" return confirm(\''.$button->getConfirmationMessage().'\');"';
        echo '<form class="actionForm" method="'.$button->getAction().'" action="'.$button->getUri().'"'.$confirm.'>';
            foreach ($button->getExtraFields() as $field => $val)
                echo '<input type="hidden" name="'.$field.'" value="'.$val.'" />';
            echo '<input type="submit" value="'.$button->getCaption().'" />';
        echo '</form>';
    }
}