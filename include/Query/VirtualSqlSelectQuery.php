<?php

namespace VirtualSql\Query;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Query\Partials\OffsetAbleSqlQuery;
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
     * @param VirtualSqlTable $from
     * @param array $config
     */
    public function __construct(VirtualSqlTable $from, array $config)
    {
        $this->selects = isset($config['selects']) && is_array($config['selects']) ? array_values(array_filter($config['selects'], fn($select) => $select instanceof VirtualSqlColumn)) : [];
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
}
