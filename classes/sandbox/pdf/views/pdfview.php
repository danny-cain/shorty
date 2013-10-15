<?php

namespace Sandbox\PDF\Views;

use CannyDain\Lib\UI\Views\ViewInterface;
use ThirdParty\FPDF\FPDF;

class PDFView implements ViewInterface
{
    /**
     * @var FPDF
     */
    protected $_pdf;

    public function display()
    {
        echo $this->_pdf->Output('','S');
    }

    /**
     * @param \ThirdParty\FPDF\FPDF $pdf
     */
    public function setPdf($pdf)
    {
        $this->_pdf = $pdf;
    }

    /**
     * @return \ThirdParty\FPDF\FPDF
     */
    public function getPdf()
    {
        return $this->_pdf;
    }

    public function getContentType()
    {
        return 'application/pdf';
    }
}