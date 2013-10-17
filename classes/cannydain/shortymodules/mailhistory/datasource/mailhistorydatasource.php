<?php

namespace CannyDain\ShortyModules\MailHistory\Datasource;

use CannyDain\Lib\DataMapping\Config\JSONFileDefinitionBuilder;
use CannyDain\Shorty\DataAccess\ShortyDatasource;

class MailHistoryDatasource extends ShortyDatasource
{
    public function registerObjects()
    {
        $builder = new JSONFileDefinitionBuilder();
        $builder->readFile(dirname(__FILE__).'/datadictionary.json', $this->_datamapper);
    }
}