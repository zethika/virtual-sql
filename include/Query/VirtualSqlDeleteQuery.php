<?php

namespace VirtualSql\Query;

use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Query\Partials\LimitAbleSqlQuery;
use VirtualSql\SqlBuilder\VirtualSqlDeleteBuilder;
use VirtualSql\Traits\QueryConditionBuilderHelpersTrait;

class VirtualSqlDeleteQuery extends LimitAbleSqlQuery
{
    use QueryConditionBuilderHelpersTrait;

    /**
     * @param VirtualSqlTable $table
     * @param array $config
     */
    public function __construct(VirtualSqlTable $table, array $config)
    {
        $this->builder = new VirtualSqlDeleteBuilder($this);
        parent::__construct($table, $config);
    }
}
