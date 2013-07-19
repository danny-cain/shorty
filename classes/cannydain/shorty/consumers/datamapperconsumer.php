<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;

interface DataMapperConsumer extends ConsumerInterface
{
    public function consumeDataMapper(DataMapper $dependency);
}