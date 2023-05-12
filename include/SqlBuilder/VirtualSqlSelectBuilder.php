<?php

namespace VirtualSql\SqlBuilder;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\Query\VirtualSqlSelectQuery;
use VirtualSql\QueryParts\Element\VirtualSqlOrderPart;
use VirtualSql\SqlBuilder\Partials\OffsetAbleSqlBuilder;
use VirtualSql\VirtualSqlConstant;

class VirtualSqlSelectBuilder extends OffsetAbleSqlBuilder
{
    /**
     * @var string[]
     */
    protected array $selectParts = [];

    /**
     * @var VirtualSqlSelectQuery
     */
    private VirtualSqlSelectQuery $query;

    /**
     * @param VirtualSqlSelectQuery $query
     */
    public function __construct(VirtualSqlSelectQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @return VirtualSqlSelectQuery
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
        $this->populateSelects();

        $string = 'SELECT ' . implode(',', $this->selectParts) . ' FROM ' . $this->getAliasedTableName($this->getQuery()->getBaseTable());

        $parts = [
            $this->buildJoinString($this->getQuery()->getJoins()),
            $this->buildWhereString($this->getQuery()->getWhere()),
            $this->buildGroupByString($this->getQuery()->getGroupBy()),
            $this->buildOrderString($this->getQuery()->getOrder()),
            $this->buildLimitString($this->getQuery()->getLimit()),
            $this->buildOffsetString($this->getQuery()->getOffset()),
        ];

        foreach ($parts as $part)
        {
            if ($part !== null)
                $string .= ' ' . $part;
        }

        return $string;
    }

    /**
     * @param array $columns
     * @return void
     */
    private function buildGroupByString(array $columns): ?string
    {
        if(count($columns) === 0)
            return null;

        $parts = array_map(fn(VirtualSqlColumn $column) => $this->getTableAliasedColumnString($column), $columns);
        return 'GROUP BY ' . implode(',',$parts);
    }

    /**
     * @param VirtualSqlOrderPart[] $orderParts
     * @return ?string
     */
    private function buildOrderString(array $orderParts): ?string
    {
        if (count($orderParts) === 0)
            return null;

        $parts = [];
        foreach ($orderParts as $part)
        {
            $parts[] = $this->getTableAliasedColumnString($part->getColumn()) . ' '.$part->getOrder();
        }

        return 'ORDER BY ' . implode(', ',$parts);
    }

    /**
     * Populates the select portion of the query
     */
    private function populateSelects()
    {
        if (count($this->query->getSelects()) === 0)
        {
            $this->selectParts[] = VirtualSqlConstant::KEYWORD_WILDCARD;
            return;
        }

        $this->selectParts = array_map(fn(VirtualSqlColumn $column) => $this->getFullyAliasedColumnString($column), $this->query->getSelects());
    }
}
