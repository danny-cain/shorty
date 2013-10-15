<?php

namespace Sandbox\Views;

use CannyDain\Shorty\Views\ShortyView;

class RichTextView extends ShortyView
{
    public function display()
    {
        echo '<textarea style="width: 100%; height: 400px;" class="richText" id="input"></textarea>';
    }
}