<?php

namespace CannyDain\Shorty\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Shorty\Consumers\RouterConsumer;

abstract class ShortyView extends HTMLView implements RouterConsumer
{
    /**
     * @var RouterInterface
     */
    protected $_router;

    public function consumeRouter(RouterInterface $router)
    {
        $this->_router = $router;
    }
}