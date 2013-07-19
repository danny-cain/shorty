<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Shorty\Consumers\DateTimeConsumer;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\IterationStatsModel;

class IterationStatsView extends HTMLView implements DateTimeConsumer
{
    /**
     * @var IterationStatsModel[]
     */
    protected $_stats;

    /**
     * @var DateFormatManager
     */
    protected $_dates;

    protected $_maxValue = 0;

    public function display()
    {
        $maxValue = 0;

        foreach ($this->_stats as $stat)
        {
            if ($stat->getEffort() > $maxValue)
                $maxValue = $stat->getEffort();
        }

        $this->_maxValue = $maxValue;

        echo '<h1>Iteration Statistics</h1>';

        $totalEffort = 0;
        $totalDays = 0;
        $remainingEffort = 0;

        echo '<div style="width: 100%; height: 300px;">';
            foreach ($this->_stats as $stat)
            {
                $this->_displayStat($stat);
                $totalEffort += $stat->getEffort();
                $totalDays += $stat->getDays();
                $remainingEffort += $stat->getRemainingEffort();
            }
        echo '</div>';

        $average= $totalEffort / $totalDays;
        $daysRemaining = $remainingEffort / $average;

        echo '<div style="margin-top: 20px;" >';
            echo 'There is '.$remainingEffort.' effort remaining';
        echo '</div>';

        echo '<div>';
            echo 'You work at an average of '.round($average, 2).' effort per day';
        echo '</div>';

        echo '<div>';
            echo 'You have approximately '.ceil($daysRemaining).' days remaining';
        echo '</div>';
    }

    protected function _displayStat(IterationStatsModel $stat)
    {
        $percentage = ($stat->getEffort() / $this->_maxValue) * 100;

        echo '<div style="box-shadow: black 2px 2px; height: '.$percentage.'%; display: inline-block; vertical-align: bottom; background-color: #ccc; border: 1px solid black;
          border-radius: 5px 5px 0 0; margin-right: -1px; ">';
            echo '<div>';
                echo $this->_dates->getFormattedDate($stat->getIteration()->getIterationStart());
            echo '</div>';

            echo '<div>';
                echo $stat->getEffort().' effort';
            echo '</div>';
        echo '</div>';
    }

    public function setStats($stats)
    {
        $this->_stats = $stats;
    }

    public function getStats()
    {
        return $this->_stats;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDateTimeManager(DateFormatManager $dependency)
    {
        $this->_dates = $dependency;
    }
}