<?php

namespace Sandbox\PDF;

use Sandbox\PDF\Models\CellModel;
use ThirdParty\FPDF\FPDF;

class ShortyFPDF extends FPDF implements PDFInterface
{
    public function writeText($text, $lineHeight = null)
    {
        if ($lineHeight == null)
            $lineHeight = $this->_getLineHeight();

        $this->Write($lineHeight, $text);
    }

    public function writeCell(CellModel $cell)
    {

    }
    
    protected function _getLineHeight()
    {
        return 5;
    }
}