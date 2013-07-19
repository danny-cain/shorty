<?php

require dirname(__FILE__).'/initialise.php';

class FT2Main implements \CannyDain\Shorty\Execution\AppMain
{
    public function main()
    {
        $helper = new \CannyDain\Shorty\UI\ViewHelpers\Models\FormDefinition();

        $helper->textbox('name', 'Name', '', 'Your name')
               ->date('dob', 'Date of Birth', 0, 1900, 2013, 'The year you were born')
               ->display();
    }
}

ShortyInit::main(new FT2Main());