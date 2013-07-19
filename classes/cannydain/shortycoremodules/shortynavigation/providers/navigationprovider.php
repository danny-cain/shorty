<?php

namespace CannyDain\ShortyCoreModules\ShortyNavigation\Providers;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\ShortyCoreModules\ShortyNavigation\DataAccess\ShortyNavigationDataAccess;
use CannyDain\ShortyCoreModules\ShortyNavigation\Models\NavItemModel;

class NavigationProvider implements \CannyDain\Shorty\Navigation\NavigationProvider, DependencyConsumer
{
    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    public function displayNavigation($containerClasses = array())
    {
        $containerClasses[] = 'shortyNavigation';
        $this->_displayNavigationByParent(0, $containerClasses);
        $this->_writeScripts();
    }

    protected function _writeScripts()
    {
        static $written = false;

        if ($written)
            return;

        $written = true;

        echo <<<HTML
<script type="text/javascript">
    $(document).ready(function()
    {
        $('.shortyNavigation').each(function()
        {
            var self = $(this);

            self.data('fatMenu',
            {
                'children' : $(),
                'initialise' : function()
                {
                    self.data('fatMenu').children = self.find('nav');
                    self.data('fatMenu').children.hide();
                    self.data('fatMenu').children.each(function()
                    {
                        var node = $(this);
                        var container = node.closest('.navItem');

                        container.attr('data-menu-bound', 'true');
                        container.hover(function(e)
                        {
                            self.data('fatMenu').showMenu(node);
                            e.stopImmediatePropagation();
                        }, function(e)
                        {
                            self.data('fatMenu').hideMenu(node);
                            e.stopImmediatePropagation();
                        });
                    });
                },
                'showMenu' : function(menuNode)
                {
                    // reposition
                    var parent = menuNode.parent();
                    var position = parent.position();
                    var left = position.left;
                    var top = position.top + parent.height();

                    menuNode.attr('data-visibility', 'visible');
                    menuNode.css('position', 'absolute');
                    menuNode.css('left', left);
                    menuNode.css('top', top);

                    menuNode.show();
                },
                'hideMenu' : function(menuNode)
                {
                    menuNode.hide();
                    menuNode.attr('data-visibility', 'hidden');
                }
            });
            self.data('fatMenu').initialise();
        });
    });
</script>
HTML;

    }

    protected function _displayNavigationByParent($parentID, $containerClasses = array())
    {
        $items = $this->datasource()->getNavItemsByParent($parentID);
        if (count($items) == 0)
            return;

        echo '<nav class="'.implode(' ', $containerClasses).'">';
            foreach ($items as $item)
                $this->_displayNavItem($item);
        echo '</nav>';
    }

    protected function _displayNavItem(NavItemModel $item)
    {
        echo '<div class="navItem">';
            if ($item->getUri() != '')
                echo '<a href="'.$item->getUri().'">'.$item->getCaption().'</a>';
            else
                echo '<span class="category">'.$item->getCaption().'</span>';

            if ($item->getRawContent() != '')
                echo $item->getRawContent();

            $this->_displayNavigationByParent($item->getId());

        echo '</div>';
    }

    protected function datasource()
    {
        static $datasource;

        if ($datasource == null)
        {
            $datasource = new ShortyNavigationDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function dependenciesConsumed()
    {
        $this->datasource()->registerObjects();
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }
}