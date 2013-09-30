<?php

namespace CannyDain\Lib\SimplePDFWriter\Helpers;

use CannyDain\Lib\SimplePDFWriter\Models\ElementData;
use CannyDain\Lib\SimplePDFWriter\PDFWriter;

class PDFTableHelper
{
    /**
     * @var PDFWriter
     */
    protected $_writer;

    public function table($columnHeaders = array(), $columnWidths = array(), $rowData = array())
    {
        $this->_writer->beginBlockElement();
            $headers = $this->_convertTableDataToElements($columnWidths, array($columnHeaders));
            $content = $this->_convertTableDataToElements($columnWidths, $rowData);

            $headersHeight = 0;
            $contentHeight = 0;

            if (count($columnHeaders) > 0)
                $headersHeight = $headers[0][0]->getHeight();

            foreach ($content as $row)
            {
                $contentHeight += $row[0]->getHeight();
            }

            $tableHeight = $headersHeight + $contentHeight;
            if ($tableHeight <= $this->_writer->canvasHeight() && !$this->_writer->canFitElementVertically($tableHeight))
                $this->_writer->pageBreak();

            if (count($columnHeaders) > 0)
                $this->_renderTableRow($headers[0], true);

            foreach ($content as $row)
                $this->_renderTableRow($row);

        $this->_writer->endBlockElement();
    }

    protected function _convertTableDataToElements($widths = array(), $data = array())
    {
        $ret = array();

        foreach ($data as $rowData)
        {
            /**
             * @var ElementData[] $row
             */
            $row = array();
            $rowHeight = 1;

            foreach ($rowData as $col => $cellContents)
            {
                $cell = new ElementData();

                $cell->setContent($this->_writer->convertTextToLines($cellContents, $widths[$col]));
                $cell->setWidth($widths[$col]);
                $cell->setHeight(count($cell->getContent()));

                if ($cell->getHeight() > $rowHeight)
                    $rowHeight = $cell->getHeight();

                $row[] = $cell;
            }

            foreach ($row as $cell)
            {
                $cell->setHeight($rowHeight * 5);
                $content = $cell->getContent();
                while (count($content) < $rowHeight)
                    $content[] = " ";

                $cell->setContent($content);
            }

            $ret[] = $row;
        }

        return $ret;
    }

    /**
     * @param \CannyDain\Lib\SimplePDFWriter\Models\ElementData[] $rowData
     * @param bool $isHeaders
     */
    protected function _renderTableRow($rowData, $isHeaders = false)
    {
        $height = 6;
        if ($isHeaders)
            $height = 7;

        $rowHeight = ($rowData[0]->getHeight() / 5) * $height;
        if (!$this->_writer->canFitElementVertically($rowHeight))
            $this->_writer->pageBreak();

        $this->_writer->beginBlockElement();

        if ($isHeaders)
        {
            $this->_writer->Bold();
            $this->_writer->SetFontSize(14);
        }

        foreach ($rowData as $cell)
        {
            $width = $cell->getWidth();

            $x = $this->_writer->GetX();
            $y = $this->_writer->GetY();

            $content = implode("\r\n", $cell->getContent());

            $this->_writer->MultiCell($width, $height, $content, 1, 'L');

            $this->_writer->SetXy($x + $width, $y);
        }

        $this->_writer->SetXY($this->_writer->canvasLeft(), $this->_writer->GetY() + $rowHeight);
        if ($isHeaders)
        {
            $this->_writer->Bold(false);
            $this->_writer->SetFontSize(12);
        }
    }

    /**
     * @param \CannyDain\Lib\SimplePDFWriter\PDFWriter $writer
     */
    public function setWriter($writer)
    {
        $this->_writer = $writer;
    }

    /**
     * @return \CannyDain\Lib\SimplePDFWriter\PDFWriter
     */
    public function getWriter()
    {
        return $this->_writer;
    }
}