<?php

namespace CannyDain\ShortyCoreModules\Diary\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Shorty\Consumers\RouterConsumer;

class UpcomingDiaryEntriesView extends HTMLView implements RouterConsumer
{
    /**
     * @var RouterInterface
     */
    protected $_router;

    public function display()
    {

    }

    public function dependenciesConsumed()
    {
        
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }
}