<?php

namespace CannyDain\Shorty\Navigation;

interface NavigationProvider
{
    public function displayNavigation($containerClasses = array());
}