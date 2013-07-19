<?php

namespace CannyDain\Sandbox\Views;

use CannyDain\Lib\UI\ResponsiveLayout\ResponsiveLayoutFactory;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;
use CannyDain\Shorty\Consumers\ResponsiveLayoutConsumer;

class ResponsiveExampleView extends HTMLView implements ResponsiveLayoutConsumer
{
    /**
     * @var ResponsiveLayoutFactory
     */
    protected $_layoutFactory;

    public function display()
    {
        $layout = $this->_layoutFactory->getLayout(10, 2, 1, 1);
        
        $layout->startRow();
        for ($i = 0; $i < 10; $i ++)
        {
            $layout->startCell();
                echo 'Cell '.$i;
            $layout->endCell();
        }
        $layout->endRow();

        $layout->startRow();
        for ($i = 0; $i < 5; $i ++)
        {
            $layout->startCell(2);
                echo 'Cell '.$i;
            $layout->endCell();
        }
        $layout->endRow();

        $layout->startRow();
            $layout->startCell(8, 2);
                echo 'Cell 0';
            $layout->endCell();
        $layout->endRow();
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeResponsiveLayoutFactory(ResponsiveLayoutFactory $dependency)
    {
        $this->_layoutFactory = $dependency;

    }
}