<?php

namespace CannyDain\Sites\DannyCain\Centralisation;

class DCCentral
{
    public function getMarketingBoxes()
    {
        return array
        (
            'Who?' => '<p>Danny is a self employed programmer based in the UK. He has 3 years professional experience in building and maintaining ECommerce and Trade Association Websites, 1 year of which was as senior developer.</p>',
            'What?' => '<p>Danny is proficient in PHP, MySQL, HTML 4/5 and CSS 2/3 but also has experience with C#, SQL Server and Java.</p>',
            'Why' => '<p>Danny has decided to strike out on his own, to build the codebase he has always wanted to build (and to talk about himself in the third person).</p>'
        );
    }

    public static function Singleton()
    {
        static $self;

        if ($self == null)
            $self = new DCCentral();

        return $self;
    }
}