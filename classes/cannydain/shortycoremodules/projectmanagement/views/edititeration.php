<?php

namespace CannyDain\ShortyCoreModules\ProjectManagement\Views;

use CannyDain\Lib\UI\Views\HTMLView;
use CannyDain\Lib\Web\Server\Request;
use CannyDain\Shorty\Consumers\FormHelperConsumer;
use CannyDain\Shorty\UI\ViewHelpers\FormHelper;
use CannyDain\ShortyCoreModules\ProjectManagement\Models\Iteration;

class EditIteration extends HTMLView implements FormHelperConsumer
{
    /**
     * @var Iteration
     */
    protected $_iteration;
    protected $_saveURI = '';

    /**
     * @var FormHelper
     */
    protected $_formHelper;

    public function display()
    {
        echo '<h1>Create/Edit Iteration</h1>';

        $this->_formHelper->startForm($this->_saveURI);
            $this->_formHelper->editDate('start', 'Iteration Start', $this->_iteration->getIterationStart(), strtotime("2013-01-01"), strtotime("+6 months"), "The date this iteration will start");
            $this->_formHelper->editDate('end', 'Iteration End', $this->_iteration->getIterationEnd(), strtotime("2013-01-01"), strtotime("+6 months"), "The date this iteration will end");
            $this->_formHelper->submitButton('Save Iteration');
        $this->_formHelper->endForm();
    }

    public function updateFromRequest(Request $request)
    {
        $start = $request->getParameter('start');
        $end = $request->getParameter('end');

        $startDate = strtotime($start['year'].'-'.$start['month'].'-'.$start['day']);
        $endDate = strtotime($end['year'].'-'.$end['month'].'-'.$end['day']);

        $this->_iteration->setIterationStart($startDate);
        $this->_iteration->setIterationEnd($endDate);
    }

    /**
     * @param \CannyDain\ShortyCoreModules\ProjectManagement\Models\Iteration $iteration
     */
    public function setIteration($iteration)
    {
        $this->_iteration = $iteration;
    }

    /**
     * @return \CannyDain\ShortyCoreModules\ProjectManagement\Models\Iteration
     */
    public function getIteration()
    {
        return $this->_iteration;
    }

    public function setSaveURI($saveURI)
    {
        $this->_saveURI = $saveURI;
    }

    public function getSaveURI()
    {
        return $this->_saveURI;
    }

    public function dependenciesConsumed()
    {

    }

    public function consumeFormHelper(FormHelper $dependency)
    {
        $this->_formHelper = $dependency;
    }
}