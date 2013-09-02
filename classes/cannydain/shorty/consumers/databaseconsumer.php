<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Lib\Database\Interfaces\DatabaseConnection;

interface DatabaseConsumer
{
    public function consumeDatabase(DatabaseConnection $database);
}