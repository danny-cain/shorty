<?php

namespace CannyDain\Lib\CommentsManager;

use CannyDain\Lib\UI\Views\ViewInterface;

interface CommentsManager
{
    /**
     * @param $guid
     * @param $objectURI
     * @return ViewInterface
     */
    public function getCommentsViewForObject($guid, $objectURI);

    /**
     * @param $guid
     * @param $returnURI
     * @return ViewInterface
     */
    public function getAdministrateCommentsView($guid, $returnURI);

    /**
     * @param $guid
     * @return int
     */
    public function getCommentCountForObject($guid);
}