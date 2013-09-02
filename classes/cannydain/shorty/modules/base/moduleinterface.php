<?php

namespace CannyDain\Shorty\Modules\Base;

use CannyDain\Shorty\Modules\Models\ModuleInfoModel;

interface ModuleInterface
{
    /**
     * Handles registering object's with the datamapper
     * @return void
     */
    public function registerDataObjects();

    /**
     * Allows the module to perform any initialisation actions (i.e. loading in session etc)
     * @return void
     */
    public function initialise();

    /**
     * @return ModuleInfoModel
     */
    public function getInfo();
}