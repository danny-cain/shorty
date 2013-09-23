<?php

namespace CannyDain\Shorty\Helpers;

use CannyDain\Shorty\Helpers\Models\UserInfo;

class UserHelper
{
    /**
     * @param $term
     * @return UserInfo[]
     */
    public function searchUsers($term)
    {
        return array();
    }

    /**
     * @param $term
     * @return UserInfo[]
     */
    public function searchGroups($term)
    {
        return array();
    }

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