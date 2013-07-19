<?php

namespace CannyDain\Lib\UI\ResponsiveLayout;

class ResponsiveLayoutFactory
{
    protected $_layouts = array();

    /**
     * @param $cellsPerRow
     * @param $cellSpacing
     * @return ResponsiveLayout
     */
    public function getLayout($cellsPerRow, $cellSpacing)
    {
        $id = $this->_getIDByDimensions($cellsPerRow, $cellSpacing, 0, 0);

        if (!isset($this->_layouts[$id]))
            $this->_layouts[$id] = $this->_factory($cellsPerRow, $cellSpacing, 0, 0);

        return $this->_layouts[$id];
    }

    protected function _factory($cellsPerRow, $cellSpacing, $borderLeft, $borderRight)
    {
        return new ResponsiveLayout($cellsPerRow, $cellSpacing, $borderLeft, $borderRight);
    }

    public function getCSS()
    {
        ob_start();
            foreach ($this->_layouts as $cells => $layout)
            {
                /**
                 * @var ResponsiveLayout $layout
                 */
                echo $layout->getCSS();
            }
        return ob_get_clean();
    }

    protected function _getIDByDimensions($cellsPerRow, $spacing, $leftWidth, $rightWidth)
    {
        return $cellsPerRow.'-'.$spacing.'-'.$leftWidth.'-'.$rightWidth;
    }
}