<?php

namespace CannyDain\ShortyModules\Comments;

use CannyDain\Shorty\Modules\Base\ShortyModule;
use CannyDain\Shorty\Modules\Models\ModuleInfoModel;
use CannyDain\ShortyModules\Comments\Datasource\CommentsDatasource;

class CommentsModule extends ShortyModule
{
    const EVENT_COMMENT_POSTED = '\\CannyDain\\ShortyModules\\Comments\\Events\\CommentPostedEvent';
    const COMMENTS_MODULE_CLASS = __CLASS__;

    /**
     * @return CommentsDatasource
     */
    public function getDatasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new CommentsDatasource();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    /**
     * Allows the module to perform any initialisation actions (i.e. loading in session etc)
     * @return void
     */
    public function initialise()
    {

    }

    /**
     * @return ModuleInfoModel
     */
    public function getInfo()
    {
        return new ModuleInfoModel('Comments Module', 'Danny Cain', '0.1');
    }
}