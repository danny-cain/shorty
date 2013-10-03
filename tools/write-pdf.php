<?php

require dirname(dirname(__FILE__)).'/classes/cannydain/initialise.php';

$file = dirname(__FILE__).'/test.pdf';
if (file_exists($file))
    unlink($file);

$writer = new \CannyDain\Lib\SimplePDFWriter\PDFWriter2();;
$xmlWriter = new \CannyDain\Lib\SimplePDFWriter\Helpers\XMLToPDF($writer);
$parser = new \CannyDain\Lib\Markup\XML\InMemoryXMLParser();

$writer->SetFont('Arial', '', 12);
$writer->AddPage();

$markup = getMarkup();
$parser->parse($markup);

$xmlWriter->writeXML($parser->getRootNode());
$writer->Output($file, 'F');

function getMarkup()
{
    return <<<HTML
<html>
    <style>
        // currently unused
    </style>

    <body>
        <p>This is my first paragraph, it has a lot of text in it because it is a very, very, very, very fucking long paragraph.</p>
        <p>This is my second paragraph, and guess what? It contains <a href="http://www.dannycain.com">a link</a></p>
    </body>
</html>
HTML;
}