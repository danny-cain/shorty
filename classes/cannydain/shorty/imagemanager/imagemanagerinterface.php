<?php

namespace CannyDain\Shorty\ImageManager;

interface ImageManagerInterface
{
    public function saveImage($sourceFile, $objectGUID, $imageType);
    public function getAllImages($objectGUID, $requiredWidth = null);
}