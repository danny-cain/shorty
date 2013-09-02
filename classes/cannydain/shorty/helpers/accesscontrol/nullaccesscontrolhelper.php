<?php

namespace CannyDain\Shorty\Helpers\AccessControl;

class NullAccessControlHelper extends AccessControlHelper
{
    /**
     * Returns an integer that represents the flags granted to this consumer for this object
     * @param $consumer
     * @param $target
     * @return int
     */
    protected function _getFlagValueForConsumerAndTarget($consumer, $target)
    {
        return 0;
    }
}