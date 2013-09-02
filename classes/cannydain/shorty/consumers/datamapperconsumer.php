<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DataMapping\DataMapperInterface;

interface DataMapperConsumer
{
    public function consumeDataMapper(DataMapperInterface $datamapper);
}