<?php

namespace VirtualSql\Query;


use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\QueryParts\Element\VirtualSqlOrderPart;
use VirtualSql\SqlBuilder\VirtualSqlUnionBuilder;
use VirtualSql\Traits\QueryConditionBuilderHelpersTrait;

class VirtualSqlUnionQuery extends VirtualSqlQuery
{
    use QueryConditionBuilderHelpersTrait;

    /**
     * @var VirtualSqlSelectQuery[]
     */
    protected array $selectQueries;
    protected ?int $limit;
    protected ?int $offset;
    protected bool $unionAll;

    /**
     * @var VirtualSqlOrderPart[]
     */
    protected array $order;

    /**
     * @param VirtualSqlTable|null $from
     * @param array $config
     * @throws InvalidQueryPartException
     */
    public function __construct(?VirtualSqlTable $from, array $config = [])
    {
        if($from !== null)
            throw new InvalidQueryPartException('Union queries can\'t have a base from table');

        parent::__construct($from);
        $this->builder = new VirtualSqlUnionBuilder($this);
        $this->selectQueries = isset($config['selectQueries']) && is_array($config['selectQueries']) ? array_values(array_filter($config['selectQueries'], fn($select) => $select instanceof VirtualSqlSelectQuery)) : [];
        $this->limit = isset($config['limit']) && is_numeric($config['limit']) ? (int)$config['limit'] : null;
        $this->offset = isset($config['offset']) && is_numeric($config['offset']) ? (int)$config['offset'] : null;
        $this->unionAll = isset($config['unionAll']) && is_bool($config['unionAll']) ? $config['unionAll'] : true;
        $this->order = isset($config['order']) && is_array($config['order']) ? array_values(array_filter($config['order'], fn($order) => $order instanceof VirtualSqlOrderPart)) : [];

    }

    /**
     * @return VirtualSqlSelectQuery[]
     */
    public function getSelectQueries(): array
    {
        return $this->selectQueries;
    }

    /**
     * @param VirtualSqlSelectQuery[] $selectQueries
     * @return VirtualSqlUnionQuery
     */
    public function setSelectQueries(array $selectQueries): VirtualSqlUnionQuery
    {
        $this->selectQueries = $selectQueries;
        return $this;
    }

    /**
     * @param VirtualSqlSelectQuery $query
     * @return $this
     */
    public function addSelectQuery(VirtualSqlSelectQuery $query): VirtualSqlUnionQuery
    {
        $this->selectQueries[] = $query;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     * @return VirtualSqlUnionQuery
     */
    public function setLimit(?int $limit): VirtualSqlUnionQuery
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     * @return VirtualSqlUnionQuery
     */
    public function setOffset(?int $offset): VirtualSqlUnionQuery
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return bool
     */
    public function getUnionAll(): bool
    {
        return $this->unionAll;
    }

    /**
     * @param bool $unionAll
     * @return VirtualSqlUnionQuery
     */
    public function setUnionAll(bool $unionAll): VirtualSqlUnionQuery
    {
        $this->unionAll = $unionAll;
        return $this;
    }

    /**
     * @return array
     */
    public function getOrder(): array
    {
        return $this->order;
    }

    /**
     * @param array $order
     * @return VirtualSqlUnionQuery
     */
    public function setOrder(array $order): VirtualSqlUnionQuery
    {
        $this->order = $order;
        return $this;
    }
}