<?php

namespace CannyDain\Lib\UI\Views;

abstract class HTMLView implements ViewInterface
{
    protected $_breadcrumbs = array();

    public function isPrintableView()
    {
        return false;
    }

    public function getContentType()
    {
        return self::CONTENT_TYPE_HTML;
    }

    protected function _displayBreadcrumbs()
    {
        echo '<nav class="breadcrumbs">';
            $isFirst = true;
            foreach ($this->_breadcrumbs as $caption => $link)
            {
                $this->_displayBreadcrumb($caption, $link, $isFirst);
                $isFirst = false;
            }

        echo '</nav>';
    }

    private function _displayBreadcrumb($caption, $link, $isFirst = false)
    {
        if (!$isFirst)
            echo ' &raquo; ';

        $output = $caption;
        if ($link != '')
            $output = '<a href="'.$link.'">'.$output.'</a>';
        else
            $output = '<span class="breadcrumb">'.$output.'</span>';

        echo $output;
    }

    public function setBreadcrumbs($breadcrumbs)
    {
        $this->_breadcrumbs = $breadcrumbs;
    }

    public function getBreadcrumbs()
    {
        return $this->_breadcrumbs;
    }
}