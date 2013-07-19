<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\UserStory;

class StorySearchResultsView extends HTMLView
{
    /**
     * @var UserStory[]
     */
    protected $_stories;

    protected $_editURITemplate = '';

    public function display()
    {
        foreach ($this->_stories as $story)
            $this->_displayStory($story);
    }

    protected function _displayStory(UserStory $story)
    {
        echo '<div class="fullUserStory">';
            echo '<div>';
                echo '<a href="'.strtr($this->_editURITemplate, array('#id#' => $story->getId())).'">';
                    echo '<strong>'.$story->getName().'</strong>';
                echo '</a>';
            echo '</div>';

            echo '<div>';
                echo '<em>'.UserStory::getStatusNameByID($story->getStatus()).'</em>';
            echo '</div>';

            echo 'As a '.$story->getTarget().' I want to '.$story->getAction().' so that '.$story->getReason();
        echo '</div>';
    }

    public function setEditURITemplate($editURITemplate)
    {
        $this->_editURITemplate = $editURITemplate;
    }

    public function getEditURITemplate()
    {
        return $this->_editURITemplate;
    }

    public function setStories($stories)
    {
        $this->_stories = $stories;
    }

    public function getStories()
    {
        return $this->_stories;
    }
}