<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Shorty\Consumers\DateTimeConsumer;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\Iteration;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\Project;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\UserStory;

class ListStoriesView extends HTMLView implements DateTimeConsumer
{
    /**
     * @var Project
     */
    protected $_project;

    /**
     * @var UserStory[]
     */
    protected $_recommendedStories = array();

    protected $_iterationStatsURI = '';

    /**
     * @var Iteration[]
     */
    protected $_iterations = array();

    protected $_editIterationURITemplate = '';
    protected $_createIterationURI = '';

    /**
     * @var DateFormatManager
     */
    protected $_dates;

    /**
     * @var UserStory[]
     */
    protected $_stories = array();
    protected $_storiesByStatus = array();
    protected $_storiesByIterationID = array();

    protected $_editStoryURITemplate = '';
    protected $_deleteStoryURITemplate = '';
    protected $_createStoryURI = '';

    /**
     * @var ViewInterface
     */
    protected $_commentsView;

    protected function _getNiceBarColour($percentage)
    {
        $colours = array
        (
            0 => '#ff0000',
            25 => '#ff4400',
            50 => '#888800',
            75 => '#44ff00',
            100 => 'green',
        );

        $ret = null;
        $currentValue = -1;
        foreach ($colours as $minValue => $barColour)
        {
            if ($minValue <= $percentage && $minValue > $currentValue)
                $ret = $barColour;
        }

        return $ret;
    }

    protected function _displayStats()
    {
        $completeStories = 0;
        $incompleteStories = 0;

        $totalEstimate = 0;
        $effort = 0;

        /**
         * @var UserStory[] $stories
         */
        foreach ($this->_storiesByStatus as $status => $stories)
        {
            foreach ($stories as $story)
            {
                if ($story->getStatus() == UserStory::STATUS_COMPLETE)
                    $effort += $story->getEstimate();
                $totalEstimate += $story->getEstimate();
            }

            if ($status == UserStory::STATUS_COMPLETE)
            {
                $completeStories += count($stories);
            }
            else
                $incompleteStories += count($stories);
        }

        $ratioComplete = $completeStories / ($completeStories + $incompleteStories);
        $percentageComplete = round($ratioComplete * 100, 2);

        echo '<p>';
            echo '<div>';
                echo 'There are '.($completeStories + $incompleteStories).' stories';
            echo '</div>';

            echo '<div>';
                echo 'Of which '.$completeStories.' are complete and '.$incompleteStories.' are incomplete';
            echo '</div>';

            echo '<div>';
                echo 'That is '.$percentageComplete.'% completion';
            echo '</div>';

            $this->_displayBar($percentageComplete);
        echo '</p>';

        echo '<p>';
            echo '<div>Total Estimated Work: '.$totalEstimate.'</div>';
            echo '<div>Effort Expended: '.$effort.'</div>';

            $percentageEffort = ($effort / $totalEstimate) * 100;
            $this->_displayBar($percentageEffort);
        echo '</p>';
    }

    protected function _displayBar($percentage)
    {
        $barColour = $this->_getNiceBarColour($percentage);
        echo '<div style="border: 1px solid black; width: 100px; background-color: white; color: black;">';
            echo '<div style=" width: '.$percentage.'%; background-color: '.$barColour.';">';
                echo '&nbsp;&nbsp;';
            echo '</div>';
        echo '</div>';
    }

    public function display()
    {
        $this->_displayBreadcrumbs();
        echo '<h1>Viewing '.$this->_project->getName().'</h1>';

        $this->_displayStats();

        $this->_displayRecommendedStories();

        echo '<div>';
            echo '<a href="'.$this->_createStoryURI.'">[create new story]</a>';
        echo '</div>';

        $statuses = array_keys($this->_storiesByStatus);
        sort($statuses);

        foreach ($statuses as $status)
        {
            $this->_displayStatus(UserStory::getStatusNameByID($status), $this->_storiesByStatus[$status]);
        }

        echo '<h2>Iterations</h2>';
        echo '<div>';
            echo '<a href="'.$this->_createIterationURI.'">Create Iteration</a>';
        echo '</div>';

        echo '<div>';
            echo '<a href="'.$this->_iterationStatsURI.'">Iteration Statistics</a>';
        echo '</div>';

        foreach ($this->_iterations as $iteration)
            $this->_displayIteration($iteration);

        if ($this->_commentsView != null)
            $this->_commentsView->display();

        echo <<<HTML
<script type="text/javascript">
    $('.sectionHeading').click(function()
    {
        $(this).next('.sectionContent').toggle();
    });

    $('.sectionContent').hide();
</script>
HTML;
    }

    protected function _displayIteration(Iteration $iteration)
    {
        /**
         * @var UserStory[] $stories
         */
        $stories = array();
        if (isset($this->_storiesByIterationID[$iteration->getId()]))
            $stories = $this->_storiesByIterationID[$iteration->getId()];

        $totalEffort = 0;

        foreach ($stories as $story)
        {
            if ($story->getStatus() != UserStory::STATUS_COMPLETE)
                continue;

            $totalEffort += $story->getEstimate();
        }

        $days = date('d', $iteration->getIterationEnd() - $iteration->getIterationStart());
        $effortPerDay = $totalEffort / $days;

        echo '<div>';
            echo '<div><strong>';
                echo 'From '.$this->_dates->getFormattedDate($iteration->getIterationStart());
                echo ' To '.$this->_dates->getFormattedDate($iteration->getIterationEnd());
            echo '</strong></div>';

            echo '<div>Effort: '.$totalEffort.'</div>';
            echo '<div>Days: '.$days.'</div>';
            echo '<div>Effort / Day: '.$effortPerDay.'</div>';
            echo '<div>';
                echo '<a href="'.strtr($this->_editIterationURITemplate, array('#id#' => $iteration->getId())).'">Edit</a>';
            echo '</div>';
        echo '</div>';
    }

    protected function _displayRecommendedStories()
    {
        echo '<h2 class="sectionHeading">Recommended Work (based on priority / estimate) ['.count($this->_recommendedStories).' stories]</h2>';

        echo '<div class="sectionContent">';
            foreach ($this->_recommendedStories as $story)
                $this->_displayStory($story);
        echo '</div>';
    }

    /**
     * @param $statusName
     * @param UserStory[] $stories
     */
    protected function _displayStatus($statusName, $stories)
    {
        echo '<h2 class="sectionHeading">'.$statusName.' ['.count($stories).' stories]</h2>';

        echo '<div class="sectionContent">';
            foreach ($stories as $story)
                $this->_displayStory($story);
        echo '</div>';
    }

    protected function _displayStory(UserStory $story)
    {
        echo '<div class="fullUserStory">';
            echo '<div>';
                echo '<a href="'.strtr($this->_editStoryURITemplate, array('#id#' => $story->getId())).'">';
                    echo '<strong>'.$story->getName().'</strong>';
                echo '</a>';
            echo '</div>';

            echo '<div>';
                echo 'Priority / Estimate: '.number_format($story->getRecommendationWeight(), 4);
            echo '</div>';

            echo '<div>';
                echo '<em>'.UserStory::getStatusNameByID($story->getStatus()).'</em>';
            echo '</div>';

            echo 'As a '.$story->getTarget().' I want to '.$story->getAction().' so that '.$story->getReason();
        echo '</div>';
    }

    protected function _displayActions(UserStory $story)
    {
        $editURI = strtr($this->_editStoryURITemplate, array('#id#' => $story->getId()));
        $deleteURI = strtr($this->_editStoryURITemplate, array('#id#' => $story->getId()));

        echo <<<HTML
<a class="actionButton" href="{$editURI}">Edit</a>
|
<form class="actionForm" method="post" action="{$deleteURI}" onsubmit="return confirm('are you sure you wish to delete this user story?');">
    <input class="actionButton" type="submit" value="Delete Story" />
</form>
HTML;

    }

    public function setRecommendedStories($recommendedStories)
    {
        $this->_recommendedStories = $recommendedStories;
    }

    public function getRecommendedStories()
    {
        return $this->_recommendedStories;
    }

    public function setCreateIterationURI($createIterationURI)
    {
        $this->_createIterationURI = $createIterationURI;
    }

    public function getCreateIterationURI()
    {
        return $this->_createIterationURI;
    }

    public function setEditIterationURITemplate($editIterationURITemplate)
    {
        $this->_editIterationURITemplate = $editIterationURITemplate;
    }

    public function getEditIterationURITemplate()
    {
        return $this->_editIterationURITemplate;
    }

    public function setIterations($iterations)
    {
        $this->_iterations = $iterations;
        $this->_sortStories($this->_stories);
    }

    public function getIterations()
    {
        return $this->_iterations;
    }

    /**
     * @param \CannyDain\Lib\UI\Views\ViewInterface $commentsView
     */
    public function setCommentsView($commentsView)
    {
        $this->_commentsView = $commentsView;
    }

    /**
     * @return \CannyDain\Lib\UI\Views\ViewInterface
     */
    public function getCommentsView()
    {
        return $this->_commentsView;
    }

    /**
     * @param UserStory[] $stories
     */
    protected function _sortStories($stories)
    {
        $this->_storiesByStatus = array();
        $this->_storiesByIterationID = array();

        foreach ($stories as $story)
        {
            $this->_storiesByStatus[$story->getStatus()][] = $story;

            $storyDate = $story->getDateCompleted();
            if ($storyDate < $story->getDateStarted())
                $storyDate = $story->getDateStarted();

            foreach ($this->_iterations as $iteration)
            {
                if ($iteration->getIterationStart() > $storyDate)
                    continue;
                if ($iteration->getIterationEnd() < $storyDate)
                    continue;

                $this->_storiesByIterationID[$iteration->getId()][] = $story;
                break;
            }
        }
    }

    public function setCreateStoryURI($createStoryURI)
    {
        $this->_createStoryURI = $createStoryURI;
    }

    public function setDeleteStoryURITemplate($deleteStoryURITemplate)
    {
        $this->_deleteStoryURITemplate = $deleteStoryURITemplate;
    }

    public function setEditStoryURITemplate($editStoryURITemplate)
    {
        $this->_editStoryURITemplate = $editStoryURITemplate;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\ProjectManagement\Models\Project $project
     */
    public function setProject($project)
    {
        $this->_project = $project;
    }

    public function setStories($stories)
    {
        $this->_stories = $stories;
        $this->_sortStories($stories);
    }

    public function setIterationStatsURI($iterationStatsURI)
    {
        $this->_iterationStatsURI = $iterationStatsURI;
    }

    public function getIterationStatsURI()
    {
        return $this->_iterationStatsURI;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDateTimeManager(DateFormatManager $dependency)
    {
        $this->_dates = $dependency;
    }
}