<?php

namespace CannyDain\ShortyCoreModules\SimpleBlog\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\Consumers\GUIDManagerConsumer;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Article;
use CannyDain\ShortyCoreModules\SimpleBlog\Models\Blog;

class SimpleBlogDatasource implements DataMapperConsumer, GUIDManagerConsumer
{
    const OBJECT_BLOG = '\\CannyDain\\ShortyCoreModules\\SimpleBlog\\Models\\Blog';
    const OBJECT_ARTICLE = '\\CannyDain\\ShortyCoreModules\\SimpleBlog\\Models\\Article';

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    /**
     * @param $searchTerm
     * @param int $blogID
     * @return Article[]
     */
    public function searchArticles($searchTerm, $blogID = null)
    {
        $whereClause = array();
        $parameters = array();

        $whereClause[] = 'content LIKE :search';
        $whereClause[] = 'tags LIKE :search';
        $whereClause[] = 'title LIKE :search';
        $parameters['search'] = '%'.$searchTerm.'%';
        if ($blogID != null)
        {
            $whereClause[] = 'blog = :blog';
            $parameters['blog'] = $blogID;
        }

        return $this->_datamapper->getObjectsWithCustomClauses(self::OBJECT_ARTICLE, $whereClause, $parameters, 'title ASC');
    }

    public function getArticleGUID($articleID)
    {
        return $this->_guids->getGUID(self::OBJECT_ARTICLE, $articleID);
    }

    /**
     * @param $uri
     * @return Blog
     */
    public function getBlogByURI($uri)
    {
        return array_shift($this->_datamapper->getAllObjectsViaEqualityFilter(self::OBJECT_BLOG, array
        (
            'uri' => $uri
        )));
    }

    /**
     * @param $blogID
     * @param $uri
     * @return Article
     */
    public function getArticleByURI($blogID, $uri)
    {
        $results = $this->_datamapper->getAllObjectsViaEqualityFilter(self::OBJECT_ARTICLE,
        array
        (
            'blog' => $blogID,
            'uri' => $uri,
        ));

        return array_shift($results);
    }

    /**
     * @param $id
     * @return Article
     */
    public function getArticleByID($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_ARTICLE, array('id' => $id));
    }

    /**
     * @param $blogID
     * @param int $count
     * @return Article[]
     */
    public function getMostRecentArticlesForBlog($blogID, $count = 10)
    {
        $results = $this->_datamapper->getAllObjectsViaEqualityFilter(self::OBJECT_ARTICLE, array
        (
            'blog' => $blogID
        ), 'posted DESC');

        return array_slice($results, 0, $count);
    }

    /**
     * @return Blog[]
     */
    public function getAllBlogs()
    {
        return $this->_datamapper->getAllObjects(self::OBJECT_BLOG);
    }

    /**
     * @param $id
     * @return Blog
     */
    public function getBlog($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_BLOG, array('id' => $id));
    }

    public function saveBlog(Blog $blog)
    {
        $this->_datamapper->saveObject($blog);
    }

    public function saveArticle(Article $article)
    {
        $this->_datamapper->saveObject($article);
    }

    public function registerObjects()
    {
        $reader = new JSONFileDefinitionBuilder();
        $reader->readFile(dirname(dirname(__FILE__)).'/datadictionary/objects.json', $this->_datamapper);
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }

    public function consumeGUIDManager(GUIDManagerInterface $dependency)
    {
        $this->_guids = $dependency;
    }
}