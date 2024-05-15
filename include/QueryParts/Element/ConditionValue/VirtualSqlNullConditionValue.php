<?php

namespace VirtualSql\QueryParts\Element\ConditionValue;

class VirtualSqlNullConditionValue extends VirtualSqlConditionValue
{
    public function getValue()
    {
        return null;
    }
}
