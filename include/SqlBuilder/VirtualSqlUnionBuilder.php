<?php

namespace VirtualSql\SqlBuilder;

use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\Query\VirtualSqlUnionQuery;
use VirtualSql\QueryParts\Element\VirtualSqlOrderPart;

class VirtualSqlUnionBuilder extends VirtualSqlBuilder
{
    private VirtualSqlUnionQuery $query;

    /**
     * @param VirtualSqlUnionQuery $query
     */
    public function __construct(VirtualSqlUnionQuery $query)
    {
        $this->query = $query;
    }

    /**
     * @return VirtualSqlUnionQuery
     */
    protected function getQuery(): VirtualSqlQuery
    {
        return $this->query;
    }

    public function getNamedParameters(): array
    {
        $parameters = [];
        foreach ($this->query->getSelectQueries() as $index => $selectQuery)
        {
            $rawParameters = $selectQuery->getNamedParameters();
            krsort($rawParameters);
            foreach ($rawParameters as $key => $value)
            {
                $parameters[$key.'_'.$index] = $value;
            }
        }

        return $parameters;
    }

    /**
     * @return string
     * @throws InvalidQueryPartException
     */
    public function getSql(): string
    {
        $parts = [];

        foreach ($this->query->getSelectQueries() as $index => $selectQuery)
        {
            $sql = $selectQuery->getSql();
            $parameters = $selectQuery->getNamedParameters();
            krsort($parameters);
            foreach ($parameters as $key => $value)
            {
                $sql = str_replace($key,$key.'_'.$index,$sql);
            }
            $parts[] = $sql;
        }

        $string = implode(($this->query->getUnionAll() ? "\nUNION ALL\n" : "\nUNION\n"),$parts);

        $parts = [
            $this->buildOrderString($this->getQuery()->getOrder()),
            $this->buildLimitString($this->getQuery()->getLimit()),
            $this->buildOffsetString($this->getQuery()->getOffset()),
        ];

        foreach ($parts as $part)
        {
            if($part !== null)
                $string .= "\n".$part;
        }

        return $string;
    }

    /**
     * @param int|null $limit
     * @return string|null
     */
    protected function buildLimitString(?int $limit): ?string
    {
        return $limit === null ? null : 'LIMIT ' . $limit;
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
     * @param int|null $offset
     * @return string|null
     */
    protected function buildOffsetString(?int $offset): ?string
    {
        return $offset === null ? null : 'OFFSET ' . $offset;
    }
}