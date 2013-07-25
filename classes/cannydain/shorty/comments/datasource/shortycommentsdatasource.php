<?php

namespace CannyDain\Shorty\Comments\Datasource;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Shorty\Comments\Models\Comment;
use CannyDain\Shorty\Consumers\DataMapperConsumer;

class ShortyCommentsDatasource implements DataMapperConsumer
{
    const OBJECT_COMMENT = '\\CannyDain\\Shorty\\Comments\\Models\\Comment';

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    /**
     * @param $guid
     * @return Comment[]
     */
    public function getCommentsForObject($guid)
    {
        return $this->_datamapper->getAllObjectsViaEqualityFilter(self::OBJECT_COMMENT, array('object' => $guid), '`posted` ASC');
    }

    public function registerObjects()
    {
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile(dirname(dirname(__FILE__)).'/datadictionary/comments.json', $this->_datamapper);
    }

    public function deleteComment($id)
    {
        $this->_datamapper->deleteObject(self::OBJECT_COMMENT, array('id' => $id));
    }

    public function saveComment(Comment $comment)
    {
        $this->_datamapper->saveObject($comment);
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}