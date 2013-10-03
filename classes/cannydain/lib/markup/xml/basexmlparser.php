<?php

namespace CannyDain\Lib\Markup\XML;

abstract class BaseXMLParser
{
    const STATE_GLOBAL = 0;
    const STATE_TAG = 1;

    protected $_state = self::STATE_GLOBAL;
    protected $_isQuoted = false;
    protected $_isEscaped = false;
    protected $_buffer = '';

    protected $_tagName = '';
    protected $_attrKey = '';
    protected $_attributes = array();

    protected $_openTags = 0;

    protected function _reset()
    {
        $this->_state = self::STATE_GLOBAL;
        $this->_isQuoted = false;
        $this->_isEscaped = false;
    }

    public function parse($xml)
    {
        $this->_reset();

        for ($i = 0; $i < strlen($xml); $i ++)
        {
            switch($this->_state)
            {
                case self::STATE_GLOBAL:
                    $this->_parseGlobalState($xml[$i]);
                    break;
                case self::STATE_TAG:
                    $this->_parseTagState($xml[$i]);
                    break;
            }
        }
    }

    protected function _parseGlobalState($char)
    {
        switch($char)
        {
            case '<':
                $text = trim($this->_buffer);
                if ($text != '')
                    $this->_notify_textContent($text);

                $this->_buffer = '';
                $this->_state = self::STATE_TAG;
                break;
            default:
                $this->_buffer .= $char;
        }
    }

    protected function _parseTagState($char)
    {
        if ($this->_isQuoted)
        {
            if ($this->_isEscaped)
                $this->_isEscaped = false;
            elseif ($char == '\\')
                $this->_isEscaped = true;
            elseif ($char == '"')
                $this->_isQuoted = false;

            $this->_buffer .= $char;
            return;
        }

        switch($char)
        {
            case '"':
                $this->_isQuoted = true;
                $this->_buffer .= '"';
                break;
            case ' ':
                if (trim($this->_buffer) == '')
                    return;

                if ($this->_tagName == '')
                {
                    $this->_tagName = trim($this->_buffer);
                    $this->_buffer = '';
                    return;
                }

                if ($this->_attrKey != '')
                {
                    $this->_attributes[$this->_attrKey] = trim($this->_buffer);
                    $this->_attrKey = '';
                    $this->_buffer = '';
                }
                break;
            case '=':
                if (trim($this->_buffer) == '')
                    return;

                if ($this->_attrKey != '')
                {
                    $this->_attributes[$this->_attrKey] = true;
                }
                $this->_attrKey = trim($this->_buffer);
                $this->_buffer = '';
                break;
            case '>':
                $text = trim($this->_buffer);
                if ($text != '')
                {
                    if ($this->_tagName == '')
                        $this->_tagName = $text;
                    elseif ($this->_attrKey == '')
                        $this->_attributes[$text] = true;
                    else
                        $this->_attributes[trim($this->_attrKey)] = $text;
                }

                $this->_processTagData();

                $this->_state = self::STATE_GLOBAL;
                $this->_tagName = '';
                $this->_buffer = '';
                $this->_attrKey = '';
                break;
            default:
                $this->_buffer .= $char;
        }
    }

    protected function _processTagData()
    {
        $closeTag = substr($this->_tagName, 0, 1) == '/';
        if ($closeTag)
            $this->_tagName = substr($this->_tagName, 1);

        $attributes = array();
        foreach ($this->_attributes as $attr => $val)
        {
            if (strpos($attr, ' ') !== false)
            {
                $singles = explode(' ', $attr);
                foreach ($singles as $attributeName)
                    $attributes[$attributeName] = true;
            }
            else
                $attributes[$attr] = $val;
        }

        $closesSelf = false;
        if (isset($attributes['/']) && $attributes['/'] == true)
        {
            $closesSelf = true;
            unset($attributes['/']);
        }

        if ($closeTag)
            $this->_notify_endTag($this->_tagName);
        else
        {
            $this->_notify_startTag($this->_tagName, $attributes);

            if ($closesSelf)
                $this->_notify_endTag($this->_tagName);
        }

        $this->_tagName = '';
        $this->_attributes = array();
        $this->_attrKey = '';
    }

    protected abstract function _notify_startTag($tag, $attributes);
    protected abstract function _notify_endTag($tag);
    protected abstract function _notify_textContent($text);
}