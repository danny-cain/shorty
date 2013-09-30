<?php

namespace CannyDain\Lib\StructuredContent\Parsers;

use CannyDain\Lib\StructuredContent\ContentElement;
use CannyDain\Lib\StructuredContent\Elements\BlockElement;
use CannyDain\Lib\StructuredContent\Elements\DocumentElement;
use CannyDain\Lib\StructuredContent\Elements\InlineElement;
use CannyDain\Lib\StructuredContent\Elements\TableCellElement;
use CannyDain\Lib\StructuredContent\Elements\TableElement;
use CannyDain\Lib\StructuredContent\Elements\TableRowElement;
use CannyDain\Lib\StructuredContent\Elements\TextElement;

class DOMHTMLParser
{
    /**
     * @param $html
     * @return DocumentElement
     */
    public function parse($html)
    {
        $ret = new DocumentElement();
        $domDoc = new \DOMDocument();

        $domDoc->loadHTML($html);
        $body = $domDoc->getElementsByTagName("body");

        for ($i = 0; $i < $body->length; $i ++)
        {
            $element = $this->_parse($body->item($i));
            if($element == null)
                continue;

            $ret->addChild($element);
        }

        return $ret;
    }

    protected function _parse(\DOMNode $element)
    {
        $parsedElement = $this->_contentElementFactory($element);

        if ($parsedElement == null)
            return null;

        if ($parsedElement instanceof TextElement)
            return $parsedElement;

        for ($i = 0; $i < $element->attributes->length; $i ++)
        {
            $attr = $element->attributes->item($i);
            $name = $attr->nodeName;
            $val = $attr->textContent;

            switch(strtolower($name))
            {
                case 'style':
                    $styles = explode(";", $val);
                    foreach ($styles as $style)
                    {
                        list($key, $val) = explode(":", $style);
                        $parsedElement->styles()->setValue($key, $val);
                    }
                    break;
                default:
                    $parsedElement->attributes()->setValue($name, $val);
            }
        }

        for ($i = 0; $i < $element->childNodes->length; $i ++)
        {
            $child = $this->_parse($element->childNodes->item($i));
            if ($child == null)
                continue;

            $parsedElement->addChild($child);
        }

        return $parsedElement;
    }

    protected function _contentElementFactory(\DOMNode $element)
    {
        if ($element instanceof \DOMText)
        {
            /**
             * @var \DOMText $element
             */
            return new TextElement($element->textContent);
        }

        $tagName = $element->nodeName;

        switch(strtolower($tagName))
        {
            case 'span':
            case 'a':
            case 'em':
            case 'strong':
            case 'b':
            case 'i':
            case 'u':
                $element = new InlineElement();
                break;
            case 'table':
                $element = new TableElement();
                break;
            case 'tr':
                $element = new TableRowElement();
                break;
            case 'td':
                $element = new TableCellElement();
                break;
            case 'th':
                $element = new TableCellElement();
                $element->styles()->setValue('font-weight', 'bold');
                $element->styles()->setValue('text-align', 'center');
                break;
            default:
                $element = new BlockElement();
        }

        switch(strtolower($tagName))
        {
            case 'p':
                $element->styles()->setValue('margin-top', 10);
                $element->styles()->setValue('margin-bottom', 10);
                break;
            case 'a':
                $element->styles()->setValue('color', 'blue');
                $element->styles()->setValue('text-decoration', 'underline');
                break;
            case 'em':
            case 'i':
                $element->styles()->setValue('font-style', 'italic');
                break;
            case 'b':
            case 'strong':
                $element->styles()->setValue('font-weight', 'bold');
                break;
            case 'u':
                $element->styles()->setValue('text-decoration', 'underline');
                break;
        }

        return $element;
    }
}