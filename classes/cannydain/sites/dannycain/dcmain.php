<?php

namespace CannyDain\Sites\DannyCain;

use CannyDain\Shorty\Execution\ShortyMain;
use CannyDain\Shorty\Sidebars\SidebarInstances\HTMLSidebar;
use CannyDain\ShortyCoreModules\UserModule\Sidebars\UserSidebar;
use CannyDain\Shorty\UI\ShortyTemplatedDocumentFactory;
use CannyDain\Sites\DannyCain\Centralisation\DCCentral;

class DCMain extends ShortyMain
{
    protected function _setupSidebar()
    {
        $userSidebar = new UserSidebar();
        $this->_dependencyInjector->applyDependencies($userSidebar);

        $this->_sidebarManager->addSidebar($userSidebar);

        foreach (DCCentral::Singleton()->getMarketingBoxes() as $title => $content)
        {
            $this->_sidebarManager->addSidebar(new HTMLSidebar($title, $content));
        }
    }

    protected function _documentFactory()
    {
        $factory = new ShortyTemplatedDocumentFactory();
        $this->_dependencyInjector->applyDependencies($factory);

        return $factory;
    }

    protected function _getHomepageURI()
    {
        return 'cannydain-sites-dannycain-controllers-homepagecontroller';
    }


}