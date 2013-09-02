<?php

namespace CannyDain\Shorty\Helpers;

class UserHelper
{
    public function getUsernameFromID($id)
    {
        return 'guest';
    }

    public function getDisplayNameFromID($id)
    {
        return 'Guest';
    }

    public function getAllUserGuids($id)
    {
        return array();
    }
}