<?php

namespace CannyDain\Sites\DannyCain;

use CannyDain\Shorty\Bootstrap\ShortyBootstrap;
use CannyDain\Shorty\Skinnable\Themes\Models\Theme;
use CannyDain\Shorty\Skinnable\Themes\ThemeManager;

class DCBootstrap extends ShortyBootstrap
{
    protected function _debugSetup()
    {

    }

    protected function _setupThemes()
    {
        ThemeManager::Singleton()->addTheme(new Theme(0, 'Blue', 'shorty.json', array
        (
            '/themes/simple-blue.css'
        )));

        ThemeManager::Singleton()->addTheme(new Theme(1, 'Green', 'shorty.json', array
        (
            '/themes/simple-green.css'
        )));

        ThemeManager::Singleton()->addTheme(new Theme(2, 'Red', 'shorty.json', array
        (
            '/themes/simple-red.css'
        )));

        /*
        ThemeManager::Singleton()->addTheme(new Theme(3, 'Wide Blue', 'dc-wide.json', array
        (
            '/themes/wide-layout/layout.css'
        )));
        */
    }
}