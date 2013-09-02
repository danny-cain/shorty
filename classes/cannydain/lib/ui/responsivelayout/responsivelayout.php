<?php

namespace CannyDain\Lib\UI\ResponsiveLayout;

class ResponsiveLayout
{
    protected $_cellsPerRow = 0;
    protected $_cellSpacing = 5;

    public function __construct($cells, $spacing)
    {
        $this->_cellsPerRow = $cells;
        $this->_cellSpacing = $spacing;
    }


    protected function _writeCSSRules($selector, $rules = array())
    {
        $ruleLines = array();
        foreach ($rules as $property => $value)
        {
            $ruleLines[] = $property.': '.$value.';';
        }

        $ruleText = implode("\r\n", $ruleLines);

        echo <<<OUTPUT
{$selector}
{
    {$ruleText};
}
OUTPUT;
    }

    protected function _getClassPrefix()
    {
        static $prefix = '';

        if ($prefix == '')
            $prefix = 'rl-'.$this->_cellSpacing.'-'.$this->_cellsPerRow;

        return $prefix;
    }

    public function startRow($classes = array())
    {
        $classes[] = $this->_getClassPrefix().'_layoutRow';

        echo '<div class="'.implode(' ', $classes).'">';
    }

    public function endRow()
    {
        echo '</div>';
    }

    public function startCell($span = 1, $offset = 0, $classes = array())
    {
        $classes[] = $this->_getClassPrefix().'_layoutCell';
        if ($span > 1)
            $classes[] = $this->_getClassPrefix().'-span-'.$span;
        if ($offset > 0)
            $classes[] = $this->_getClassPrefix().'-offset-'.$offset;

        echo '<div class="'.implode(' ', $classes).'">';
    }

    public function endCell()
    {
        echo '</div>';
    }

    public function getCSS()
    {
        $cellWidth = $this->_getCellPercentageWidth();

        ob_start();

        $this->_writeCSSRules('.'.$this->_getClassPrefix().'_layoutRow', array());
        $this->_writeCSSRules('.'.$this->_getClassPrefix().'_layoutCell', array
        (
            'display' => 'inline-block',
            'box-sizing' => 'border-box',
            'margin-left' => $this->_cellSpacing.'%',
            'width' => $cellWidth.'%',
            'moz-box-sizing' => 'border-box',
            'vertical-align' => 'top',
        ));

        //$this->_writeCSSRules('.'.$this->_getClassPrefix().'_layoutCell:first-child', array('margin-left' => $this->_cellSpacing.'%'));

        for ($i = 1; $i < $this->_cellsPerRow; $i ++)
        {
            $spanName = 'span-'.($i + 1);
            $offsetName = 'offset-'.($i);

            $cellWidth = $i * $this->_getCellPercentageWidth();
            $innerMarginWidth = $i * $this->_cellSpacing;

            $spanWidth = $cellWidth + $innerMarginWidth + $this->_getCellPercentageWidth();
            $offsetWidth = $spanWidth + $this->_cellSpacing - $this->_getCellPercentageWidth();
            $this->_writeCellCSS($spanName, $offsetName, $spanWidth, $offsetWidth);
        }

        return ob_get_clean();
    }

    protected function _writeCellCSS($spanName, $offsetName, $spanWidth, $offsetWidth)
    {
        $this->_writeCSSRules('.'.$this->_getClassPrefix().'-'.$spanName, array('width' => $spanWidth.'%'));
        $this->_writeCSSRules('.'.$this->_getClassPrefix().'-'.$offsetName, array('margin-left' => $offsetWidth.'%'));
        //$this->_writeCSSRules('.'.$this->_getClassPrefix().'-'.$offsetName.':first-child', array('margin-left' => $offsetWidth.'%'));
    }

    protected function _getTotalSpacing()
    {
        return ($this->_cellsPerRow + 1) * $this->_cellSpacing;
    }

    protected function _getCellPercentageWidth()
    {
        $spacingWidth = $this->_getTotalSpacing();
        $totalCellWidth = 100 - $spacingWidth;
        $cellWidth = $totalCellWidth / $this->_cellsPerRow;

        return $cellWidth;
    }
}