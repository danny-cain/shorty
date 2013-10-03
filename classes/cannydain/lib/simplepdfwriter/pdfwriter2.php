<?php

namespace CannyDain\Lib\SimplePDFWriter;

use ThirdParty\FPDF\FPDF;

class PDFWriter2 extends FPDF
{
    const ORIENTATION_PORTRAIT = 'P';
    const UNIT_MILIMETRES = 'mm';
    const SIZE_A4 = 'A4';

    public function __construct($orientation = self::ORIENTATION_PORTRAIT, $unit = self::UNIT_MILIMETRES, $size = self::SIZE_A4)
    {
        parent::FPDF($orientation, $unit, $size);
    }

    public function setHTMLColour($colour)
    {
        $colourMappings = array
        (
            'blue' => '#00F',
            'black' => '#000',
            'white' => '#FFF',
        );

        if (isset($colourMappings[$colour]))
            $colour = $colourMappings[$colour];

        $colour = substr($colour, 1);

        if (strlen($colour) == 3)
        {
            $r = substr($colour,0, 1);
            $g = substr($colour,1, 1);
            $b = substr($colour,2, 1);

            $r .= $r;
            $g .= $g;
            $b .= $b;
        }
        else
        {
            $r = substr($colour, 0, 2);
            $g = substr($colour, 2, 2);
            $b = substr($colour, 4, 2);
        }

        $r = base_convert($r, 16, 10);
        $g = base_convert($g, 16, 10);
        $b = base_convert($b, 16, 10);

        $this->SetTextColor($r, $g, $b);
    }
}