<?php

namespace CannyDain\Lib\StructuredContent\Output;

use CannyDain\Lib\StructuredContent\ContentElementInterface;
use CannyDain\Lib\StructuredContent\Elements\BlockElement;
use CannyDain\Lib\StructuredContent\Elements\DocumentElement;
use CannyDain\Lib\StructuredContent\Elements\InlineElement;
use CannyDain\Lib\StructuredContent\Elements\TableElement;
use CannyDain\Lib\StructuredContent\Elements\TextElement;

class HTMLOutput
{
    public function display(ContentElementInterface $element)
    {
        if ($element instanceof TableElement)
            $this->_displayTable($element);
        elseif($element instanceof TextElement)
            $this->_displayText($element);
        elseif ($element instanceof DocumentElement)
            $this->_displayDocumentElement($element);
        else
            $this->_displayElement($element);
    }

    protected function _displayTable(TableElement $element)
    {

    }

    protected function _displayText(TextElement $element)
    {
        echo $element->getText();
    }

    protected function _displayElement(ContentElementInterface $element)
    {
        $attributes = array();
        $styles = array();

        foreach ($element->styles()->getKeys() as $style)
        {
            $val = $element->styles()->getValue($style);
            $styles[] = $style.': '.$val;
        }

        foreach ($element->attributes()->getKeys() as $attr)
        {
            $val = $element->attributes()->getValue($attr);
            $attributes[] = $attr.'="'.htmlentities($val, ENT_QUOTES, 'UTF-8').'"';
        }

        $attributes[] = 'style="'.implode(';', $styles).'"';

        switch($element->getFlowType())
        {
            case ContentElementInterface::FLOW_TYPE_INLINE:
                $tag = 'span';
                break;
            default:
                $tag = 'div';
                break;
        }

        echo '<'.$tag.' '.implode(' ', $attributes).'>';
            foreach ($element->getChildren() as $child)
                $this->display($child);
        echo '</'.$tag.'>';
    }

    protected function _displayDocumentElement(DocumentElement $element)
    {
        echo '<!DOCTYPE html>';
        echo '<html>';
            echo '<head>';
            echo '</head>';

            echo '<body>';
                foreach($element->getChildren() as $child)
                    $this->display($child);
            echo '</body>';
        echo '</html>';
    }
}