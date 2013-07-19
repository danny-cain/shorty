<?php

namespace CannyDain\Lib\CommentsManager;

use CannyDain\Lib\UI\Views\NullHTMLView;
use CannyDain\Lib\UI\Views\ViewInterface;

class NullCommentsManager implements CommentsManager
{
    /**
     * @param $guid
     * @param $objectURI
     * @return ViewInterface
     */
    public function getCommentsViewForObject($guid, $objectURI)
    {
        return new NullHTMLView();
    }

    /**
     * @param $guid
     * @param $returnURI
     * @return ViewInterface
     */
    public function getAdministrateCommentsView($guid, $returnURI)
    {
        return new NullHTMLView();
    }

    /**
     * @param $guid
     * @return int
     */
    public function getCommentCountForObject($guid)
    {
        return 0;
    }
}