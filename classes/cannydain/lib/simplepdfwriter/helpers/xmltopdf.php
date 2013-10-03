<?php

namespace CannyDain\Lib\SimplePDFWriter\Helpers;

use CannyDain\Lib\Markup\XML\Models\TagNode;
use CannyDain\Lib\Markup\XML\Models\TextNode;
use CannyDain\Lib\SimplePDFWriter\Models\Stylesheets;
use CannyDain\Lib\SimplePDFWriter\PDFWriter2;
use CannyDain\Lib\StructuredContent\Elements\TextElement;

class XMLToPDF
{
    /**
     * @var PDFWriter2
     */
    protected $_writer;
    protected $_uri = '';
    protected $_currentMargin = 0;

    public function __construct(PDFWriter2 $writer)
    {
        $this->_writer = $writer;
    }

    public function writeXML(TagNode $node)
    {
        /**
         * @var TagNode $body
         */
        $body = array_shift($node->getElementsByTagName('body'));

        foreach ($body->getChildren() as $child)
        {
            if ($child instanceof TagNode)
                $this->_writeTag($child);
            else
                $this->_writeText($child);
        }
    }

    protected function _writeText(TextNode $text)
    {
        $this->_writer->Write($this->_lineHeight(), $text->getContent(), $this->_uri);
    }

    protected function _resetToLeft()
    {
        if ($this->_writer->GetX() > 0)
        {
            $this->_writer->SetY($this->_writer->GetY() + $this->_lineHeight());
        }
    }

    protected function _writeTag(TagNode $tag)
    {
        switch(strtolower($tag->getName()))
        {
            case 'div':
                $this->_resetToLeft();
                break;
            case 'p':
                $this->_resetToLeft();
                $margin = $this->_lineHeight() - $this->_currentMargin;

                if ($margin > 0)
                    $this->_writer->SetY($this->_writer->GetY() + $margin);
                break;
            case 'a':
                $this->_writer->Write($this->_lineHeight(), ' ');
                $this->_uri = $tag->getAttributeValue('href');
                $this->_writer->setHTMLColour('blue');
                break;
        }

        foreach ($tag->getChildren() as $child)
        {
            if ($child instanceof TagNode)
                $this->_writeTag($child);
            elseif ($child instanceof TextNode)
                $this->_writeText($child);
        }

        switch(strtoupper($tag->getName()))
        {
            case 'div':
                $this->_resetToLeft();
                break;
            case 'p':
                $this->_resetToLeft();
                $margin = $this->_lineHeight() - $this->_currentMargin;

                if ($margin > 0)
                    $this->_writer->SetY($this->_writer->GetY() + $margin);
                break;
            case 'a':
                $this->_uri = '';
                $this->_writer->setHTMLColour('black');
                break;
        }
    }

    protected function _lineHeight()
    {
        return 5;
    }
}