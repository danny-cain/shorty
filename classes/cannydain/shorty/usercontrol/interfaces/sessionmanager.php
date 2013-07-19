<?php

namespace CannyDain\Shorty\UserControl\Interfaces;

interface SessionManager
{
    public function getCurrentSessionID();
    public function getCurrentUserID();
    public function setCurrentUserID($id);
}