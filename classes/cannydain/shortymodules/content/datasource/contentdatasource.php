<?php

namespace CannyDain\ShortyModules\Content\Datasource;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;
use CannyDain\ShortyModules\Content\Models\ContentPage;

class ContentDatasource extends ShortyDatasource
{
    /**
     * @return ContentPage[]
     */
    public function getAllPages()
    {
        return $this->_datamapper->getAllObjects(ContentPage::TYPE_NAME_CONTENT_PAGE);
    }

    public function deletePage($id)
    {
        $this->_datamapper->deleteObject(ContentPage::TYPE_NAME_CONTENT_PAGE, array('id' => $id));
    }

    public function createPage()
    {
        $page = new ContentPage();
        $this->_dependencies->applyDependencies($page);

        return $page;
    }

    /**
     * @param $id
     * @return ContentPage
     */
    public function loadPage($id)
    {
        return $this->_datamapper->loadObject(ContentPage::TYPE_NAME_CONTENT_PAGE, array('id' => $id));
    }

    public function registerObjects()
    {
        $file = dirname(__FILE__).'/datadictionary.json';
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile($file, $this->_datamapper);
    }
}