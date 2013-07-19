<?php

namespace CannyDain\ShortyCoreModules\ShortyNavigation\DataAccess;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Lib\DataMapping\DataMapper;
use CannyDain\Shorty\Consumers\DataMapperConsumer;
use CannyDain\ShortyCoreModules\ShortyNavigation\Models\NavItemModel;

class ShortyNavigationDataAccess implements DataMapperConsumer
{
    const OBJECT_NAV_ITEM = '\\CannyDain\\ShortyCoreModules\\ShortyNavigation\\Models\\NavItemModel';

    /**
     * @var DataMapper
     */
    protected $_datamapper;

    public function registerObjects()
    {
        $file = dirname(dirname(__FILE__)).'/datadictionary/nav.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }

    public function saveNavItem(NavItemModel $nav)
    {
        $this->_datamapper->saveObject($nav);
    }

    /**
     * @param $parentID
     * @return NavItemModel[]
     */
    public function getNavItemsByParent($parentID)
    {
        return $this->_datamapper->getAllObjectsViaEqualityFilter(self::OBJECT_NAV_ITEM, array
        (
            'parent' => $parentID,
        ), 'orderIndex ASC');
    }

    /**
     * @param $id
     * @return NavItemModel
     */
    public function getNavItemByID($id)
    {
        return $this->_datamapper->loadObject(self::OBJECT_NAV_ITEM, array('id' => $id));
    }

    public function deleteNavItem($id)
    {
        $this->_datamapper->deleteObject(self::OBJECT_NAV_ITEM, array('id' => $id));
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeDataMapper(DataMapper $dependency)
    {
        $this->_datamapper = $dependency;
    }

}