<?php

namespace VirtualSql\SqlBuilder;

use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\VirtualSqlDeleteQuery;
use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\SqlBuilder\Partials\LimitAbleSqlBuilder;

class VirtualSqlDeleteBuilder extends LimitAbleSqlBuilder
{
    /**
     * @return bool
     */
    protected function disableAliases(): bool {
        return true;
    }

    /**
     * @var VirtualSqlDeleteQuery
     */
    private VirtualSqlDeleteQuery $query;

    /**
     * @param VirtualSqlDeleteQuery $query
     */
    public function __construct(VirtualSqlDeleteQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @return VirtualSqlDeleteQuery
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
        $string = 'DELETE FROM ' . $this->getAliasedTableName($this->getQuery()->getBaseTable());

        $parts = [
            $this->buildJoinString($this->getQuery()->getJoins()),
            $this->buildWhereString($this->getQuery()->getWhere()),
            $this->buildLimitString($this->getQuery()->getLimit()),
        ];

        foreach ($parts as $part)
        {
            if ($part !== null)
                $string .= ' ' . $part;
        }

        return $string;
    }
}
