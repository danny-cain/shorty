<?php

namespace CannyDain\Lib\Database\PDO\Results;

use CannyDain\Lib\Database\Interfaces\DatabaseQueryResultInterface;

class PDOQueryResult implements DatabaseQueryResultInterface
{
    /**
     * @var \PDOStatement
     */
    protected $_statement;

    public function __toString()
    {
        ob_start();
            echo '<table>';
                echo '<tr>';
                    foreach ($this->getColumnNames() as $name)
                        echo '<th>'.$name.'</th>';
                echo '</tr>';

                while ($row = $this->nextRow_IndexedArray())
                {
                    echo '<tr>';
                        foreach ($row as $field)
                            echo '<td>'.$field.'</td>';
                    echo '</tr>';
                }
            echo '</table>';
        return ob_get_clean();
    }

    public function __construct(\PDOStatement $statement)
    {
        $this->_statement = $statement;
    }

    public function nextRow_AssociativeArray()
    {
        return $this->_statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function nextRow_IndexedArray()
    {
        return $this->_statement->fetch(\PDO::FETCH_NUM);
    }

    public function getColumnNames()
    {
        $ret = array();
        for ($i = 0; $i < $this->_statement->columnCount(); $i ++)
        {
            $meta = $this->_statement->getColumnMeta($i);
            $ret[] = $meta['name'];
        }

        return $ret;
    }

    public function getErrorMessage()
    {
        return '';
    }

    public function getRowCount()
    {
        return $this->_statement->rowCount();
    }
}