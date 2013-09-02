<?php

namespace CannyDain\ShortyModules\Comments\Datasource;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\ShortyModules\Comments\Models\Comment;

class CommentsDatasource extends ShortyDatasource
{
    public function deleteComment($id)
    {
        $this->_datamapper->deleteObject(Comment::COMMENT_OBJECT_TYPE, array('id' => $id));
    }

    public function getCommentsCount($guid)
    {
        return count($this->loadAllCommentsForObject($guid));
    }

    public function createComment()
    {
        $model = new Comment();
        $this->_dependencies->applyDependencies($model);

        return $model;
    }

    /**
     * @param $guid
     * @return Comment[]
     */
    public function loadAllCommentsForObject($guid)
    {
        return $this->_datamapper->getObjectsWithCustomClauses(Comment::COMMENT_OBJECT_TYPE, array
        (
            'guid = :guid'
        ), array
        (
            'guid' => $guid
        ), 'posted ASC');
    }

    /**
     * @param $id
     * @return Comment
     */
    public function loadComment($id)
    {
        return $this->_datamapper->loadObject(Comment::COMMENT_OBJECT_TYPE, array('id' => $id));
    }

    public function registerObjects()
    {
        $file = dirname(__FILE__).'/datadictionary.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }
}