<?php

namespace CannyDain\Shorty\Controllers;

use CannyDain\Shorty\Views\ShortyHomepageView;

class ShortyHomeController extends ShortyController
{
    public function Index()
    {
        /**
         * @var ShortyHomepageView $view
         */
        $view = $this->_viewFactory->getView('\\CannyDain\\Shorty\\Views\\ShortyHomepageView');
        $this->_dependencies->applyDependencies($view);

        return $view;
    }
}