<?php

namespace Sandbox\PDF\Models;

class TableModel
{
    /**
     * @var ColumnModel
     */
    protected $_columns = array();
    protected $_content = array();

    public function countColumns() { return count($this->_columns); }

    public function getColumn($index)
    {
        if (!isset($this->_columns[$index]))
            return null;

        return $this->_columns[$index];
    }

    public function addColumn(ColumnModel $column)
    {
        $this->_columns[] = $column;
    }

    public function addRow($row)
    {
        if (count($row) != $this->countColumns())
            throw new \Exception("Invalid Column Count");

        $this->_content[] = $row;
    }

    public function countRows() { return count($this->_content); }
    public function getRow($index)
    {
        if (!isset($this->_content[$index]))
            return array();

        return $this->_content[$index];
    }
}