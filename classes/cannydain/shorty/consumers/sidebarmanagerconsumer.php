<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;
use CannyDain\Shorty\Sidebars\SidebarManager;

interface SidebarManagerConsumer extends ConsumerInterface
{
    public function consumeSidebarManager(SidebarManager $manager);
}