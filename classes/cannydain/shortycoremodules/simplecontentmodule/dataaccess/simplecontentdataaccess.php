<?php

namespace CannyDain\ShortyCoreModules\SimpleContentModule\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Lib\GUIDS\GUIDManagerInterface;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\Shorty\Consumers\GUIDManagerConsumer;
use CannyDain\ShortyCoreModules\SimpleContentModule\Models\ContentPage;

class SimpleContentDataAccess implements DataMapperConsumer, GUIDManagerConsumer
{
    const OBJECT_PAGE = '\\CannyDain\\ShortyCoreModules\\SimpleContentModule\\Models\\ContentPage';

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    /**
     * @var GUIDManagerInterface
     */
    protected $_guids;

    public function registerObjects()
    {
        $reader = new JSONFileDefinitionBuilder();
        $reader->readFile(dirname(dirname(__FILE__)).'/datadictionary/objects.json', $this->_datamapper);
    }

    public function getPageGUID($pageID)
    {
        return $this->_guids->getGUID(self::OBJECT_PAGE, $pageID);
    }

    /**
     * @return ContentPage[]
     */
    public function getAllPages()
    {
        return $this->_datamapper->getAllObjects(self::OBJECT_PAGE);
    }

    /**
     * @param $id
     * @return ContentPage
     */
    public function getPageByID($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_PAGE, array('id' => $id));
    }

    /**
     * @param $friendlyID
     * @return ContentPage
     */
    public function getPageByFriendlyID($friendlyID)
    {
        $results = $this->_datamapper->getAllObjectsViaEqualityFilter(self::OBJECT_PAGE, array
        (
            'friendlyID' => $friendlyID
        ));
        if (count($results) == 0)
            return null;

        return array_shift($results);
    }

    public function deletePage($pageID)
    {
        $this->_datamapper->deleteObject(self::OBJECT_PAGE, $pageID);
    }

    public function savePage(ContentPage $page)
    {
        $this->_datamapper->saveObject($page);
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeGUIDManager(GUIDManagerInterface $dependency)
    {
        $this->_guids = $dependency;
    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }
}