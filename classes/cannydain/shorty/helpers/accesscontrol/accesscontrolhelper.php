<?php

namespace CannyDain\Shorty\Helpers\AccessControl;

use CannyDain\Lib\Types\BitMaskFlag;
use CannyDain\Shorty\Consumers\SessionConsumer;
use CannyDain\Shorty\Consumers\UserConsumer;
use CannyDain\Shorty\Helpers\SessionHelper;
use CannyDain\Shorty\Helpers\UserHelper;

abstract class AccessControlHelper implements UserConsumer, SessionConsumer
{
    /**
     * @var UserHelper
     */
    protected $_users;

    /**
     * @var SessionHelper
     */
    protected $_session;

    /**
     * Returns an integer that represents the flags granted to this consumer for this object
     * @param $consumer
     * @param $target
     * @return int
     */
    protected abstract function _getFlagValueForConsumerAndTarget($consumer, $target);

    public function checkUserHasAllFlags($targetGUID, $flags = array(), $userID = null)
    {
        foreach ($this->_getUserGUIDs($userID) as $guid)
        {
            $permissions = new BitMaskFlag($this->_getFlagValueForConsumerAndTarget($guid, $targetGUID));
            $flags = $permissions->getFlagsThatArentSet($flags);

            if (count($flags) == 0)
                return true;
        }

        return count($flags) == 0;
    }

    public function checkUserHasAnyFlag($targetGUID, $flags = array(), $userID = null)
    {
        foreach ($this->_getUserGUIDs($userID) as $guid)
        {
            $permissions = new BitMaskFlag($this->_getFlagValueForConsumerAndTarget($guid, $targetGUID));
            if ($permissions->containsAnyOf($flags))
                return true;
        }

        return false;
    }

    protected function _getUserGUIDs($userID = null)
    {
        if ($userID == null)
            $userID = $this->_session->getUserID();

        return $this->_users->getAllUserGuids($userID);
    }

    public function consumerUserHelper(UserHelper $helper)
    {
        $this->_users = $helper;
    }

    public function consumeSession(SessionHelper $session)
    {
        $this->_session = $session;
    }
}