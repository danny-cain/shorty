<?php

namespace CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML\EnhancedElements;

use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML\BaseHTMLContainerElement;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\Elements\HTML\BaseHTMLElement;
use CannyDain\Lib\UI\Response\TemplatedDocuments\Models\TemplatedDocumentElement;
use CannyDain\Lib\UI\Views\ViewInterface;

class SimpleHeroSpot extends TemplatedDocumentElement
{
    protected $_items = array();
    protected $_id = '';
    protected $_classes = array();

    public function __construct($id = '', $classes = array(), $items = array())
    {
        $this->_id = $id;
        $this->_classes = $classes;
        $this->_items = $items;
    }

    public function display(ViewInterface $view)
    {
        echo '<div id="'.$this->_id.'" class="'.implode(' ', $this->_classes).'">';
            foreach ($this->_items as $item)
            {
                echo '<div class="herospotItem">';
                    echo $item;
                echo '</div>';
            }
        echo '</div>';
    }
}