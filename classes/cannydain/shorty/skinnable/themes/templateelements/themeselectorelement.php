<?php

namespace CannyDain\Shorty\Skinnable\Themes\TemplateElements;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\RequestConsumer;
use CannyDain\Shorty\Skinnable\Themes\ThemeManager;

class ThemeSelectorElement extends TemplatedDocumentElement implements RequestConsumer
{
    /**
     * @var Request
     */
    protected $_request;

    public function display(ViewInterface $view)
    {
        if ($this->_request->getParameter('skinnableAction') == 'changeTheme')
        {
            ThemeManager::Singleton()->setCurrentThemeID($this->_request->getParameter('theme'));
            header("Location: /".$this->_request->getResource());
        }
        $id = ThemeManager::Singleton()->getCurrentThemeID();

        echo '<div class="themeSelector">';
            echo '<form method="post" action="/">';
                echo '<input type="hidden" name="skinnableAction" value="changeTheme" />';
                echo 'Theme:';
                echo ' <select name="theme">';
                    foreach (ThemeManager::Singleton()->getAllThemes() as $theme)
                    {
                        $selected = '';
                        if ($theme->getId() == $id)
                            $selected = ' selected="selected"';

                        echo '<option value="'.$theme->getId().'"'.$selected.'>'.$theme->getName().'</option>';
                    }
                echo '</select>';
                echo '<input type="submit" value="Select Theme" />';
            echo '</form>';
        echo '</div>';
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRequest(Request $dependency)
    {
        $this->_request = $dependency;
    }
}