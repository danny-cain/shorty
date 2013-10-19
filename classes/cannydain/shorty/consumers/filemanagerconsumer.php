<?php

namespace CannyDain\Shorty\Consumers;

use CannyDain\Shorty\FileManager\FileManagerInterface;

interface FileManagerConsumer
{
    public function consumeFileManager(FileManagerInterface $fileManager);
}