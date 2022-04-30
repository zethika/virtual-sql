<?php

namespace VirtualSql\SqlBuilder;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\VirtualSqlInsertQuery;
use VirtualSql\Query\VirtualSqlQuery;

class VirtualSqlInsertBuilder extends VirtualSqlBuilder
{
    /**
     * @var VirtualSqlInsertQuery
     */
    private VirtualSqlInsertQuery $query;

    /**
     * @param VirtualSqlInsertQuery $query
     */
    public function __construct(VirtualSqlInsertQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @return VirtualSqlInsertQuery
     */
    protected function getQuery(): VirtualSqlQuery
    {
        return $this->query;
    }

    /**
     * @return string
     * @throws InvalidQueryPartException
     */
    public function getSql(): string
    {
        $string = 'INSERT INTO ' . $this->getAliasedTableName($this->getQuery()->getBaseTable()) . ' (';
        $string .= implode(',', array_map(fn(VirtualSqlColumn $column) => $column->getColumn(), $this->getQuery()->getColumns()));
        $string .= ') VALUES ';

        $sets = [];
        foreach ($this->getQuery()->getValueSets() as $set)
        {
            $parts = array_map(fn(VirtualSqlColumn $column) => $this->parseAddValue($column, $set[$column->getColumn()] ?? null), $this->getQuery()->getColumns());
            $sets[] = '(' . implode(',', $parts) . ')';
        }

        $string .= implode(',', $sets);

        return $string . $this->buildOnDuplicateKeyPart();
    }

    /**
     * @return string
     */
    private function buildOnDuplicateKeyPart(): string
    {
        $string = '';
        if (count($this->getQuery()->getOnDuplicateUpdateColumns()) !== 0)
        {
            $string .= ' ON DUPLICATE KEY UPDATE ';
            $parts = array_map(fn(VirtualSqlColumn $column) => $column->getColumn() . '=VALUES(' . $column->getColumn() . ')', $this->getQuery()->getOnDuplicateUpdateColumns());
            $string .= implode(', ', $parts);
        }
        return $string;
    }
}
