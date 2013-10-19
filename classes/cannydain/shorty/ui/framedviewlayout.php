<?php

namespace CannyDain\Shorty\UI;

use CannyDain\Lib\UI\Response\Layouts\Layout;

class FramedViewLayout extends Layout
{
    protected function _displayPageFoot()
    {

    }

    protected function _displayDocumentHead()
    {
        echo '<!DOCTYPE html>';
        echo '<html>';
            echo '<head>';
                $this->_outputStylesheets();
                $this->_outputScripts();
            echo '</head>';

            echo '<body>';
    }

    protected function _outputStylesheets()
    {
        echo '<link rel="stylesheet" type="text/css" href="/styles.php" />';
    }

    protected function _outputScripts()
    {
        echo '<script type="application/javascript" src="/scripts.php"></script>';
        echo '<script type="application/javascript" src="/unbundledscripts/tinymce/tinymce.min.js"></script>';

        echo <<<HTML
<script type="text/javascript">
    tinymce.init(
    {
        selector : "textarea.richText",
        plugins :
        [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor filemanager importcss"
        ],
        toolbar1 : "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
        toolbar2 : "print preview | forecolor backcolor emoticons | insertinternallink insertinternalimage",
        convert_urls : false,
        content_css : "/styles/contentstyles.css",
        importcss_append : true,
        importcss_selector_converter : function(selector)
        {
            return this.convertSelectorToFormat(selector);
        }
    });
</script>
HTML;

    }

    protected function _displayPageHead()
    {
    }


    protected function _displayDocumentFoot()
    {
            echo '</body>';
        echo '</html>';
    }

    public function getContentType()
    {
        return self::CONTENT_TYPE_HTML;
    }
}