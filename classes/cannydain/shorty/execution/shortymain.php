<?php

namespace CannyDain\Shorty\Execution;

use CannyDain\Shorty\Sidebars\SidebarInstances\HTMLSidebar;
use CannyDain\ShortyCoreModules\ProjectManagement\Sidebars\ProjectManagementSidebar;
use CannyDain\ShortyCoreModules\UserModule\Sidebars\UserSidebar;

class ShortyMain extends BaseMain
{
    protected function _setupSidebar()
    {
        $userSidebar = new UserSidebar();
        $this->_dependencyInjector->applyDependencies($userSidebar);

        $pmSidebar = new ProjectManagementSidebar();
        $this->_dependencyInjector->applyDependencies($pmSidebar);

        $this->_sidebarManager->addSidebar($userSidebar);
        $this->_sidebarManager->addSidebar($pmSidebar);
        $this->_sidebarManager->addSidebar(new HTMLSidebar('What Is Shorty?', '<p>Shorty is a website framework designed for easy use by developer\'s, enabling you to get a powerful, functional website for cheap</p>'));
        $this->_sidebarManager->addSidebar(new HTMLSidebar('Who is Danny Cain?', '<p>Danny Cain is a software developer based in Oxford, UK.  He has aprox. 3 years professional experience in building and maintaining websites for Trade Associations and E-Shops.</p>'));
    }
}