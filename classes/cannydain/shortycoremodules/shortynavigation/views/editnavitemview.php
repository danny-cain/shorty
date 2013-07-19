<?php

namespace CannyDain\ShortyCoreModules\ShortyNavigation\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\ShortyNavigation\Models\NavItemModel;

class EditNavItemView extends HTMLView implements FormHelperConsumer
{
    /**
     * @var NavItemModel
     */
    protected $_item;

    protected $_saveURI = '';

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    public function display()
    {
        echo '<h1>Edit Menu Item</h1>';
        $this->_formHelper->startForm($this->_saveURI);
            $this->_formHelper->editText('caption', 'Caption', $this->_item->getCaption(), 'The title of the item');
            $this->_formHelper->editText('uri', 'URI', $this->_item->getUri(), 'The location this item should link to');
            $this->_formHelper->editText('order', 'Order', $this->_item->getOrderIndex(), 'The order this item will appear in');
            $this->_formHelper->editRichText('content', 'Content', $this->_item->getRawContent(), 'The content of this item');
            $this->_formHelper->submitButton('Save');
        $this->_formHelper->endForm();
    }

    public function updateModel(Request $request)
    {
        $this->_item->setCaption($request->getParameter('caption'));
        $this->_item->setUri($request->getParameter('uri'));
        $this->_item->setOrderIndex($request->getParameter('order'));
        $this->_item->setRawContent($request->getParameter('content'));
    }

    public function setSaveURI($saveURI)
    {
        $this->_saveURI = $saveURI;
    }

    public function getSaveURI()
    {
        return $this->_saveURI;
    }

    /**
     * @param \CannyDain\ShortyCoreModules\ShortyNavigation\Models\NavItemModel $item
     */
    public function setItem($item)
    {
        $this->_item = $item;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\ShortyNavigation\Models\NavItemModel
     */
    public function getItem()
    {
        return $this->_item;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}