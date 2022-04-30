<?php

namespace VirtualSql\SqlBuilder;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\Query\VirtualSqlSelectQuery;
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
