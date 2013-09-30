<?php

require dirname(dirname(__FILE__)).'/classes/cannydain/initialise.php';

$file = dirname(__FILE__).'/test.pdf';
if (file_exists($file))
    unlink($file);

$writer = new \CannyDain\Lib\SimplePDFWriter\PDFWriter();
$tableHelper = new \CannyDain\Lib\SimplePDFWriter\Helpers\PDFTableHelper();

$data = getData();

$writer->pageBreak();
$writer->SetFont('Arial','', 12);

$tableHelper->setWriter($writer);

$writer->Bold();
$writer->centredText('Danny\'s Test PDF');
$writer->Bold(false);

$writer->AddFont('josefinsans', '', 'josefinsans.php');
$writer->AddFont('josefinsans', 'B', 'josefinsansb.php');
$writer->AddFont('josefinsans', 'I', 'josefinsansi.php');
$writer->AddFont('josefinsans', 'BI', 'josefinsansbi.php');

foreach ($writer->FontFiles as $fontFile)
{
    $writer->Write(5, print_r($fontFile, true));
    $writer->lineBreak();
}

foreach ($writer->CoreFonts as $font)
{
    $writer->SetFont($font);
    $writer->Write(5, $font);
    $writer->lineBreak();
}

$writer->SetFont('josefinsans', '');

$writer->writeText('This is danny cain\'s test pdf ');
$writer->writeLink('http://www.dannycain.com', '(dannycain.com) ');
$writer->writeText('it is written using SimplePDFWriter, an extension to FPDF that allows me to easily create PDF\'s, even ones with tables!');

$tableHelper->table($data['headers'], $data['columns'], $data['rows']);
$writer->Output($file, 'F');

function getData()
{
    return array
    (
        'headers' => array
        (
            'Name',
            'Age',
            'Info'
        ),
        'columns' => array
        (
            30,
            30,
            60
        ),
        'rows' => array
        (
            array("Danny Cain", "29", "Senior Developer and Programmer Extraordinaire"),
            array("Danny Cain", "29", "Senior Developer and Programmer Extraordinaire"),
            array("Danny Cain", "29", "Senior Developer and Programmer Extraordinaire"),
            array("Danny Cain", "29", "Senior Developer and Programmer Extraordinaire"),
            array("Danny Cain", "29", "Senior Developer and Programmer Extraordinaire"),
            array("Danny Cain", "29", "Senior Developer and Programmer Extraordinaire"),
            array("Danny Cain", "29", "Senior Developer and Programmer Extraordinaire"),
            array("Danny Cain", "29", "Senior Developer and Programmer Extraordinaire"),
            array("Danny Cain", "29", "Senior Developer and Programmer Extraordinaire"),
        )
    );
}