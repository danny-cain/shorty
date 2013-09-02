<?php

namespace CannyDain\Lib\Forms\Views;

use CannyDain\Lib\Web\Server\Request;

interface InputFieldView
{
    public function display();
    public function updateModel(Request $request);
}