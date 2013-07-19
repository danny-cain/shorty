<?php

namespace CannyDain\Shorty\Views;

use CannyDain\Lib\UI\Views\HTMLView;

class ShortyHomepageView extends HTMLView
{
    public function display()
    {


        echo <<<HTML
<div style=" text-align: center; font-size: 1.6em; font-weight: bold; ">
    SHORTY HAS ARRIVED!
</div>

<p>The all new, fully functional CMS is here, and it\s here to stay!</p>
<p>Designed by developer's, for developer's, Shorty boasts a powerful dependency injection system,
a robust library that is easily extensible, and adherence to the Model View Controller pattern.</p>

<p>Making it easy to quickly create a brand new site, and containing support for templating, Shorty
is sure to make your developer's squeal with delight!</p>

<p>
Shorty automatically handles HTTP Request parsing, and will make the parsed request available site wide
for those consumer's that require it.  It also handles response writing, has built in tokeniser support,
easy overriding of views and a robust module system to easily extend the functionality.  Shorty also has
built in "commentAnywhere" functionality that allows developer's to add comments to a page with minimal
effort whilst maintaining style standards.
</p>
HTML;
    }
}