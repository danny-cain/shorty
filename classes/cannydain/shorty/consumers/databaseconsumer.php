<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\Database\Interfaces\DatabaseConnection;
use CannyDain\Lib\DependencyInjection\Interfaces\ConsumerInterface;

interface DatabaseConsumer extends ConsumerInterface
{
    public function consumeDatabaseConnection(DatabaseConnection $dependency);
}