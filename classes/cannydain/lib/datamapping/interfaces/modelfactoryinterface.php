<?php

namespace CannyDain\Lib\DataMapping\Interfaces;

interface ModelFactoryInterface
{
    /**
     * @param $type
     * @param array $rowData
     * @return object
     */
    public function createModel($type, $rowData);
}