<?php

namespace CannyDain\ShortyCoreModules\SimpleBlog\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;

class SearchBlogView extends HTMLView implements FormHelperConsumer
{
    protected $_searchTerm = '';
    protected $_searchExecuted = false;

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    protected $_searchURI = '';

    /**
     * @var SearchResultsView
     */
    protected $_searchResults;

    public function display()
    {
        echo '<h2>Search Blog(s)</h2>';
        $this->_formHelper->startForm($this->_searchURI, 'GET');
            $this->_formHelper->hiddenField('searchBlogs', 1);
            $this->_formHelper->editText('term', 'Search Term', $this->_searchTerm);
            $this->_formHelper->submitButton('Search');
        $this->_formHelper->endForm();

        if ($this->_searchResults != null)
            $this->_searchResults->display();
    }

    /**
     * @param \CannyDain\ShortyCoreModules\SimpleBlog\Views\SearchResultsView $searchResults
     */
    public function setSearchResults($searchResults)
    {
        $this->_searchResults = $searchResults;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\SimpleBlog\Views\SearchResultsView
     */
    public function getSearchResults()
    {
        return $this->_searchResults;
    }

    public function setSearchURI($searchURI)
    {
        $this->_searchURI = $searchURI;
    }

    public function getSearchURI()
    {
        return $this->_searchURI;
    }

    public function wasSearchRequested() { return $this->_searchExecuted; }
    public function getSearchTerm() { return $this->_searchTerm; }

    public function updateModelFromRequest(Request $request)
    {
        if ($request->getParameter('searchBlogs') == '1')
        {
            $this->_searchExecuted = true;
            $this->_searchTerm = $request->getParameter('term');
        }

    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}