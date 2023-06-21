<?php

namespace VirtualSql\QueryParts\Element\ConditionValue;

use VirtualSql\Query\VirtualSqlSelectQuery;
use VirtualSql\Query\VirtualSqlUnionQuery;

class VirtualSqlCompositeQueryConditionValue extends VirtualSqlConditionValue
{
    private VirtualSqlSelectQuery|VirtualSqlUnionQuery $query;

    public function __construct(VirtualSqlSelectQuery|VirtualSqlUnionQuery $query)
    {
        $this->query = $query;
    }

    public function getValue(): VirtualSqlSelectQuery|VirtualSqlUnionQuery
    {
        return $this->query;
    }
}