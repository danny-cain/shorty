<?php

namespace CannyDain\ShortyCoreModules\SimpleContentModule\Views;

use CannyDain\Lib\Utils\Date\DateFormatManager;
use CannyDain\Shorty\Consumers\DateTimeConsumer;

class FunkyPageView extends ContentPageView implements DateTimeConsumer
{
    /**
     * @var DateFormatManager
     */
    protected $_dateTimeFormat;

    public function display()
    {
        echo '<div class="funkySimpleContentPage">';
            echo '<div class="pageMeta">';
                echo '<h1>'.$this->_page->getTitle().'</h1>';
                echo '<div class="author">';
                    echo 'Last Modified By '.$this->_page->getAuthorName().' on '.$this->_dateTimeFormat->getFormattedDateTime($this->_page->getLastModified());
                echo '</div>';
            echo '</div>';

            echo '<div class="pageContent">';
                echo $this->_page->getContent();
            echo '</div>';

            $this->_displayComments();
        echo '</div>';
    }

    public function consumeDateTimeManager(DateFormatManager $dependency)
    {
        $this->_dateTimeFormat = $dependency;
    }
}