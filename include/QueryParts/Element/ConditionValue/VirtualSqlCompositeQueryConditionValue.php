<?php

namespace VirtualSql\QueryParts\Element\ConditionValue;

use VirtualSql\Query\VirtualSqlSelectQuery;

class VirtualSqlCompositeQueryConditionValue extends VirtualSqlConditionValue
{
    private VirtualSqlSelectQuery $query;

    public function __construct(VirtualSqlSelectQuery $query)
    {
        $this->query = $query;
    }

    public function getValue(): VirtualSqlSelectQuery
    {
        return $this->query;
    }
}