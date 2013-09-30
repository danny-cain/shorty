<?php

namespace CannyDain\Lib\SimplePDFWriter;

use ThirdParty\FPDF\FPDF;

class PDFWriter extends FPDF
{
    protected $_isBold = false;
    protected $_isItalic = false;
    protected $_isUnderline = false;

    public function __construct()
    {
        parent::FPDF();
        $this->SetFont('Arial');
    }

    public function Bold($setBold = true)
    {
        $this->_isBold = $setBold;
        $this->_updateFont();
    }

    public function Italic($setItalic = true)
    {
        $this->_isItalic = $setItalic;
        $this->_updateFont();
    }

    public function Underline($setUnderline = true)
    {
        $this->_isUnderline = $setUnderline;
        $this->_updateFont();
    }

    protected function _updateFont()
    {
        $style = '';

        if ($this->_isBold)
            $style .= 'B';
        if ($this->_isItalic)
            $style .= 'I';
        if ($this->_isUnderline)
            $style .= 'U';

        $this->SetFont('', $style);
    }

    function SetFont($family, $style = '', $size = 0)
    {
        $this->_isBold = false;
        $this->_isItalic = false;
        $this->_isUnderline = false;

        for ($i = 0; $i < strlen($style); $i ++)
        {
            switch($style[$i])
            {
                case 'B':
                    $this->_isBold = true;
                    break;
                case 'I':
                    $this->_isItalic = true;
                    break;
                case 'U':
                    $this->_isUnderline = true;
                    break;
            }
        }

        parent::SetFont($family, $style, $size);
    }

    public function centredText($text)
    {
        $this->beginBlockElement();
            $this->Cell(0, 0, $text,0, 0, 'C');
        $this->endBlockElement();
    }

    public function getRemainingWidth()
    {
        return $this->canvasWidth() - ($this->GetX() - $this->lMargin);
    }

    protected function _extractStringOfMaxWidth($sourceString, $maxWidth)
    {
        $wordBuffer = '';
        $lineBuffer = '';

        $breakChars = array("\t", "\r", "\n", " ");

        for ($i = 0; $i < strlen($sourceString); $i ++)
        {
            $char = $sourceString[$i];
            $isBreak = in_array($char, $breakChars);
            $wordBuffer .= $char;

            if ($isBreak)
            {
                $tempLine = $lineBuffer.$wordBuffer;
                $width = $this->GetStringWidth($tempLine);


                if ($width >= $maxWidth)
                {
                    $lineBuffer = trim($lineBuffer);
                    if ($lineBuffer == '')
                        return trim($wordBuffer);

                    return $lineBuffer;
                }
                $lineBuffer = $tempLine;
                $wordBuffer = '';
            }
        }

        $tempLine = $lineBuffer.$wordBuffer;
        $width = $this->GetStringWidth($tempLine);
        if ($width >= $maxWidth)
            return $lineBuffer;

        return $tempLine;
    }

    function Write($h, $txt, $link = '')
    {
        $firstLine = true;
        while (strlen($txt) > 0)
        {
            $line = $this->_extractStringOfMaxWidth($txt, $this->getRemainingWidth());
            if (strlen($line) >= strlen($txt))
                $txt = '';
            else
                $txt = substr($txt, strlen($line) + 1);

            if (!$firstLine)
                $this->lineBreak(false);

            $firstLine = true;

            parent::Write($h, $line, $link);
        }
    }

    public function lineBreak($forceNewLine = true)
    {
        if ($forceNewLine || $this->GetX() > 0)
            $this->SetY($this->GetY() + 5);

        $this->SetX($this->lMargin);
    }

    public function writeLink($uri, $text)
    {
        $this->Write(5, $text, $uri);
    }

    public function writeText($text)
    {
        $this->Write(5, $text);
    }

    public function pageBreak()
    {
        $this->AddPage();
        $this->SetX($this->lMargin);
        $this->SetY($this->tMargin);
    }

    public function canFitElementVertically($elementHeight)
    {
        $y = $this->GetY();
        $height = $this->canvasHeight();

        return $height - $y >= $elementHeight;
    }

    public function convertTextToLines($text, $containerWidth)
    {
        $wordBuffer = '';
        $lineBuffer = '';
        $ret = array();
        $lastBreak = 0;

        for ($i = 0; $i < strlen($text); $i ++)
        {
            $wordBuffer .= $text[$i];

            switch($text[$i])
            {
                case ' ':
                case "\t":
                case "\r":
                case "\n":
                    $temp = $lineBuffer.$wordBuffer;
                    if ($this->GetStringWidth(trim($temp)) > $containerWidth - 10)
                    {
                        $ret[] = trim($lineBuffer);
                        $lineBuffer = $wordBuffer;
                    }
                    else
                        $lineBuffer .= $wordBuffer;

                    $wordBuffer = '';
                    break;
            }

            if ($text[$i] == "\r"|| $text[$i] == "\n")
            {
                $ret[] = $lineBuffer;
                $lineBuffer = '';
            }
        }
        if (trim($wordBuffer) != '')
            $lineBuffer .= $wordBuffer;

        if (trim($lineBuffer) != '')
            $ret[] = trim($lineBuffer);

        return $ret;
    }

    public function beginBlockElement($spacing = 0)
    {
        $x = $this->GetX();
        $y = $this->GetY();

        if ($this->GetX() > $this->lMargin || $spacing > 0)
        {
            $this->SetXY($this->canvasLeft(), $this->GetY() + 5 + $spacing);
        }

        $newX = $this->GetX();
        $newY = $this->GetY();
        /*
        $this->SetXY($x, $y);
            parent::Write(2.5, $x.",".$y." - ".$newX.",".$newY);
        $this->SetXY($newX, $newY);
        /* */
    }

    public function endBlockElement($spacing = 0)
    {
        if ($this->GetX() > $this->canvasLeft())
        {
            $this->SetX($this->canvasLeft());
            $this->SetY($this->GetY() + 5 + $spacing);
        }
    }

    public function canvasHeight()
    {
        return $this->h - $this->tMargin - $this->bMargin;
    }

    public function canvasWidth()
    {
        return $this->w - $this->lMargin - $this->rMargin;
    }

    public function canvasLeft()
    {
        return $this->lMargin;
    }

    public function canvasRight()
    {
        return $this->w - $this->rMargin;
    }
}