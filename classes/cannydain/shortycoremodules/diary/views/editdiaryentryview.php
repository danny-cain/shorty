<?php

namespace CannyDain\ShortyCoreModules\Diary\Views;

use CannyDain\Lib\Routing\Interfaces\RouterInterface;
use CannyDain\Lib\Routing\Models\Route;
use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\Consumers\RouterConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\Diary\Models\DiaryEntry;

class EditDiaryEntryView extends HTMLView implements RouterConsumer, FormHelperConsumer
{
    /**
     * @var FormHelper
     */
    protected $_formHelper;

    /**
     * @var RouterInterface
     */
    protected $_router;

    /**
     * @var DiaryEntry
     */
    protected $_entry;

    /**
     * @var Route
     */
    protected $_saveRoute;

    public function display()
    {
        echo '<h1>Edit/Create Diary Entry</h1>';

        $this->_formHelper->startForm($this->_router->getURI($this->_saveRoute));
            $this->_formHelper->editText('task', 'Task', $this->_entry->getText(), 'The appointment/meeting name');
            $this->_formHelper->editCheckbox('public', 'Is Public?', '1', $this->_entry->isPublic(), 'Whether this meeting is publicly viewable');
            $this->_formHelper->editDateTime('start', 'Start', $this->_entry->getStart(), strtotime("-1 month"), strtotime("+1 year"), 'The date/time that this appointment starts');
            $this->_formHelper->editDateTime('end', 'End', $this->_entry->getEnd(), strtotime("-1 month"), strtotime("+1 year"), 'The date/time that this appointment ends');
            $this->_formHelper->submitButton('Save Task');
        $this->_formHelper->endForm();
    }

    public function updateFromRequest(Request $request)
    {
        $this->_entry->setText($request->getParameter('task'));
        $this->_entry->setStart($this->_getDateTimeFromRequest($request, 'start'));
        $this->_entry->setEnd($this->_getDateTimeFromRequest($request, 'end'));

        if ($request->getParameter('public') == '1')
            $this->_entry->makePublic();
        else
            $this->_entry->makePrivate();
    }

    protected function _getDateTimeFromRequest(Request $request, $fieldname)
    {
        $val = $request->getParameter($fieldname);

        return strtotime($val['year'].'-'.$val['month'].'-'.$val['day'].' '.$val['hour'].':'.$val['minute'].':00');
    }

    /**
     * @param \CannyDain\ShortyCoreModules\Diary\Models\DiaryEntry $entry
     */
    public function setEntry($entry)
    {
        $this->_entry = $entry;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\Diary\Models\DiaryEntry
     */
    public function getEntry()
    {
        return $this->_entry;
    }

    /**
     * @param \CannyDain\Lib\Routing\Models\Route $saveRoute
     */
    public function setSaveRoute($saveRoute)
    {
        $this->_saveRoute = $saveRoute;
    }

    /**
     * @return \CannyDain\Lib\Routing\Models\Route
     */
    public function getSaveRoute()
    {
        return $this->_saveRoute;
    }

    public function dependenciesConsumed()
    {
        
    }

    public function consumeRouter(RouterInterface $dependency)
    {
        $this->_router = $dependency;
    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}