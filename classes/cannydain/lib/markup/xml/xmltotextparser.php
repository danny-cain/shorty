<?php

namespace CannyDain\Lib\Markup\XML;

class XMLToTextParser extends BaseXMLParser
{
    protected $_openTags = 0;

    protected function _notify_startTag($tag, $attributes)
    {
        $label = '<'.strtoupper($tag);

        $attributeLabels = array();
        foreach ($attributes as $attr => $val)
        {
            if ($val === true)
                $attributeLabels[] = $attr;
            else
                $attributeLabels[] = $attr.':'.$val;
        }

        if (count($attributeLabels) > 0)
        {
            $label .= ' ('.implode(' ', $attributeLabels).')';
        }

        $label = str_pad('', $this->_openTags * 4, ' ', STR_PAD_LEFT).$label;

        echo $label."\r\n";

        $this->_openTags ++;
    }

    protected function _notify_endTag($tag)
    {
        $this->_openTags --;
    }

    protected function _notify_textContent($text)
    {
        $text = str_pad('', $this->_openTags * 4, ' ', STR_PAD_LEFT).$text;

        echo $text."\r\n";
    }
}