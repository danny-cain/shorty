<?php

namespace CannyDain\Lib\UI\Views;

abstract class HTMLView implements ViewInterface
{
    protected $_breadcrumbs = array();
    protected $_isAjax = false;
    protected $_isFramed = false;

    /**
     * Todo: promote this to the ViewInterface
     * @param bool $isAjax
     */
    public function setIsAjax($isAjax)
    {
        $this->_isAjax = $isAjax;
    }

    public function setIsFramed($isFramed)
    {
        $this->_isFramed = $isFramed;
    }

    public function getIsFramed()
    {
        return $this->_isFramed;
    }

    /**
     * Todo: promote this to the ViewInterface
     * @return bool
     */
    public function getIsAjax()
    {
        return $this->_isAjax;
    }

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