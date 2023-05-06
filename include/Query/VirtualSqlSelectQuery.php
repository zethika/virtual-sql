<?php

namespace VirtualSql\Query;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Query\Partials\OffsetAbleSqlQuery;
use VirtualSql\QueryParts\Element\VirtualSqlOrderPart;
use VirtualSql\SqlBuilder\VirtualSqlSelectBuilder;
use VirtualSql\Traits\QueryConditionBuilderHelpersTrait;

class VirtualSqlSelectQuery extends OffsetAbleSqlQuery
{
    use QueryConditionBuilderHelpersTrait;

    /**
     * @var VirtualSqlColumn[]
     */
    private array $selects;

    /**
     * @var VirtualSqlOrderPart[]
     */
    private array $order;

    /**
     * @param VirtualSqlTable $from
     * @param array $config
     */
    public function __construct(VirtualSqlTable $from, array $config)
    {
        $this->selects = isset($config['selects']) && is_array($config['selects']) ? array_values(array_filter($config['selects'], fn($select) => $select instanceof VirtualSqlColumn)) : [];
        $this->order = isset($config['order']) && is_array($config['order']) ? array_values(array_filter($config['order'], fn($order) => $order instanceof VirtualSqlOrderPart)) : [];

        $this->builder = new VirtualSqlSelectBuilder($this);
        parent::__construct($from, $config);
    }

    /**
     * @return VirtualSqlColumn[]
     */
    public function getSelects(): array
    {
        return $this->selects;
    }

    /**
     * @param VirtualSqlColumn[] $selects
     * @return VirtualSqlSelectQuery
     */
    public function setSelects(array $selects): VirtualSqlSelectQuery
    {
        $this->selects = $selects;
        return $this;
    }

    /**
     * @param VirtualSqlColumn $columnSelect
     * @return VirtualSqlSelectQuery
     */
    public function addSelect(VirtualSqlColumn $columnSelect): VirtualSqlSelectQuery
    {
        $this->selects[] = $columnSelect;
        return $this;
    }

    /**
     * @return VirtualSqlOrderPart[]
     */
    public function getOrder(): array
    {
        return $this->order;
    }

    /**
     * @param VirtualSqlOrderPart[] $order
     * @return VirtualSqlSelectQuery
     */
    public function setOrder(array $order): VirtualSqlSelectQuery
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param VirtualSqlOrderPart $part
     * @return VirtualSqlSelectQuery
     */
    public function addOrderPart(VirtualSqlOrderPart $part): VirtualSqlSelectQuery
    {
        $this->order[] = $part;
        return $this;
    }
}
