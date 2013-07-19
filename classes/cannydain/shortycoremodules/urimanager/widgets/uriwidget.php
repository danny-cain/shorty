<?php

namespace CannyDain\ShortyCoreModules\URIManager\Widgets;

use CannyDain\Lib\DependencyInjection\DependencyInjector;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\DependencyConsumer;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Routing\Widgets\AssignURIWidget;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\URIManager\DataAccess\URIManagerDataAccess;
use CannyDain\ShortyCoreModules\URIManager\Models\URIMappingModel;

class URIWidget extends AssignURIWidget implements FormHelperConsumer, DependencyConsumer
{
    /**
     * @var URIMappingModel
     */
    protected $_uri;
    protected $_fieldname = '';

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var DependencyInjector
     */
    protected $_dependencies;

    /**
     * @param Request $request
     * @return bool
     */
    public function updateAndSaveFromRequest(Request $request)
    {
        if ($request->getParameter($this->_fieldname) != '')
        {
            $this->_uri->setUri($request->getParameter($this->_fieldname));
            $this->datasource()->saveURI($this->_uri);
        }

        return true;
    }

    public function display()
    {
        $this->_formHelper->editText($this->_fieldname, 'uri', $this->_uri->getUri(), 'The URI that will be used to access this object (no leading /)');
    }

    protected function datasource()
    {
        static $datasource = null;

        if ($datasource == null)
        {
            $datasource = new URIManagerDataAccess();
            $this->_dependencies->applyDependencies($datasource);
        }

        return $datasource;
    }

    public function setFieldname($fieldname)
    {
        $this->_fieldname = $fieldname;
    }

    public function getFieldname()
    {
        return $this->_fieldname;
    }

    public function setUri($uri)
    {
        $this->_uri = $uri;
    }

    public function getUri()
    {
        return $this->_uri;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }

    public function consumeDependencyInjector(DependencyInjector $dependency)
    {
        $this->_dependencies = $dependency;
    }
}