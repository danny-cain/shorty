<?php

namespace CannyDain\Shorty\Events;

class EventManager
{
    const SHORTY_EVENT_BOOTSTRAP_COMPLETE = '\\CannyDain\\Shorty\\Events\\Events\\BootstrapCompleteEvent';
    const SHORTY_EVENT_REGISTER_DATA = '\\CannyDain\\Shorty\\Events\\Events\\RegisterDataEvent';

    protected $_subscribedEvents = array();

    public function subscribeToEvents($subscriber, $events = array())
    {
        foreach ($events as $event)
            $this->_subscribedEvents[$event][]= $subscriber;
    }

    public function triggerEvent($event, $params = array())
    {
        if (!isset($this->_subscribedEvents[$event]))
            return;

        $method = $this->_validateEventAndReturnMethodName($event, $params);

        foreach ($this->_subscribedEvents[$event] as $subscriber)
            call_user_func_array(array($subscriber, $method), $params);
    }

    protected function _validateEventAndReturnMethodName($eventInterface, $params)
    {
        $reflectionClass = new \ReflectionClass($eventInterface);
        $method = $this->_getEventMethodName($reflectionClass, $params);

        if ($method == null)
            throw new \Exception("Unable to locate event method");

        return $method;
    }

    protected function _getEventMethodName(\ReflectionClass $eventInterface, $params)
    {
        foreach ($eventInterface->getMethods() as $method)
        {
            if (substr(strtolower($method->getName()), 0, 7) != '_event_')
                continue;

            if (!$this->_validateParams($method, $params))
                continue;

            return $method->getName();
        }

        return null;
    }

    protected function _validateParams(\ReflectionMethod $method, $params)
    {
        if (count($params) < $method->getNumberOfRequiredParameters())
            return false;

        $methodParams = $method->getParameters();
        foreach ($params as $param)
        {
            /**
             * @var \ReflectionParameter $methodParam
             */
            $methodParam = array_shift($methodParams);
            if ($methodParam->getClass() == null)
                continue;

            if (!is_a($param, $methodParam->getClass()->getName()))
                return false;
        }

        return true;
    }
}