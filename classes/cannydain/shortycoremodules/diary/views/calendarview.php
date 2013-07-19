<?php

namespace CannyDain\ShortyCoreModules\Diary\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\ShortyCoreModules\Diary\Models\DiaryEntry;

class CalendarView extends HTMLView implements RouterConsumer
{
    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var DiaryEntry[]
     */
    protected $_entries = array();
    protected $_month = 1;
    protected $_year = 2013;

    /**
     * @var Route
     */
    protected $_createRoute;

    public function display()
    {
        echo '<div id="calendar"></div>';
        echo '<a href="'.$this->_router->getURI($this->_createRoute).'">New Entry</a>';
        $this->_writeScript();
    }

    protected function _writeScript()
    {
        echo <<<HTML
<script type="text/javascript">
    CalendarConstants =
    {
        DAY_SUNDAY : 0,
        DAY_MONDAY : 1,
        DAY_TUESDAY : 2,
        DAY_WEDNESDAY : 3,
        DAY_THURSDAY : 4,
        DAY_FRIDAY : 5,
        DAY_SATURDAY : 6
    };

    $(document).ready(function()
    {
        var calendar = $('#calendar');

        calendar.data('calendar',
        {
            year : {$this->_year},
            month : {$this->_month},
            element : calendar,
            initialise : function()
            {
                this.drawCalendar();
            },
            drawCalendar : function()
            {
                this.element.empty();
                for (var i = 0; i < 7; i ++)
                {
                    var day = this.getDayName(i);
                    this.element.append('<div class="header">' + day + '</div>');
                }

                for (i = 0; i < this.getStartDayOfMonth(this.month); i ++)
                {
                    this.element.append('<div class="day disabled"></div>');
                }

                for (i = 1; i <= this.getDaysInMonth(this.month); i ++)
                {
                    this.element.append('<div class="day">' + i + '</div>');
                }
            },
            getDayName : function(day)
            {
                var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

                return days[day];
            },
            getDaysInMonth : function(month)
            {
                return 30;
            },
            getStartDayOfMonth : function(month)
            {
                return CalendarConstants.DAY_MONDAY;
            }
        });

        calendar.data('calendar').initialise();
    });
</script>
HTML;

    }

    public function setEntries($entries)
    {
        $this->_entries = $entries;
    }

    public function getEntries()
    {
        return $this->_entries;
    }

    public function setMonth($month)
    {
        $this->_month = $month;
    }

    public function getMonth()
    {
        return $this->_month;
    }

    public function setYear($year)
    {
        $this->_year = $year;
    }

    public function getYear()
    {
        return $this->_year;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $createRoute
     */
    public function setCreateRoute($createRoute)
    {
        $this->_createRoute = $createRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getCreateRoute()
    {
        return $this->_createRoute;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}