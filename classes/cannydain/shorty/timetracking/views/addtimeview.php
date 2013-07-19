<?php

namespace CannyDain\Shorty\TimeTracking\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\TimeTracking\Models\TimeEntry;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;

class AddTimeView extends HTMLView implements FormHelperConsumer
{
    protected $_postURI = '';

    protected $_returnURI = '';

    protected $_objectGUID = '';

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var TimeEntry
     */
    protected $_entry;

    public function display()
    {
        $start = $this->_entry->getStart();
        $end = $this->_entry->getEnd();


        if ($start <= 0 && $end <= 0)
        {
            $start = strtotime("-15 minutes");
            $end = time();
        }

        echo '<h2>Add Time</h2>';
        $this->_formHelper->startForm($this->_postURI);
            $this->_formHelper->hiddenField('return', $this->_returnURI);
            $this->_formHelper->hiddenField('guid', $this->_objectGUID);
            $this->_formHelper->editDateTime('start', 'Start', $start, strtotime("-1 year"), strtotime("+1 year"), 'The date/time this entry began');
            $this->_formHelper->editDateTime('end', 'End', $end, strtotime("-1 year"), strtotime("+1 year"), 'The date/time this entry finished');
            $this->_formHelper->editText('comment', 'Comment', $this->_entry->getComment(), 'Any comment to add to this entry');
            $this->_formHelper->submitButton('Add Time');
        $this->_formHelper->endForm();
    }

    public function updateModelFromRequest(Request $request)
    {
        $this->_entry->setStart($this->_getDateTimeFromRequest($request, 'start'));
        $this->_entry->setEnd($this->_getDateTimeFromRequest($request, 'end'));
        $this->_entry->setComment($request->getParameter('comment'));
        $this->_returnURI = $request->getParameter('return');
        $this->_objectGUID = $request->getParameter('guid');
    }

    protected function _getDateTimeFromRequest(Request $request, $field)
    {
        $parts = $request->getParameter($field);

        $strDateTime = $parts['year'].'-'.$parts['month'].'-'.$parts['day'].' '.$parts['hour'].':'.$parts['minute'].':00';
        return strtotime($strDateTime);
    }

    public function setObjectGUID($objectGUID)
    {
        $this->_objectGUID = $objectGUID;
    }

    public function getObjectGUID()
    {
        return $this->_objectGUID;
    }

    public function setReturnURI($returnURI)
    {
        $this->_returnURI = $returnURI;
    }

    public function getReturnURI()
    {
        return $this->_returnURI;
    }

    /**
     * @param \CannyDain\Shorty\TimeTracking\Models\TimeEntry $entry
     */
    public function setEntry($entry)
    {
        $this->_entry = $entry;
    }

    /**
     * @return \CannyDain\Shorty\TimeTracking\Models\TimeEntry
     */
    public function getEntry()
    {
        return $this->_entry;
    }

    public function setPostURI($postURI)
    {
        $this->_postURI = $postURI;
    }

    public function getPostURI()
    {
        return $this->_postURI;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}