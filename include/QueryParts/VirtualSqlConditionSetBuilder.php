<?php

namespace VirtualSql\QueryParts;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\QueryParts\Element\ConditionValue\VirtualSqlConditionValue;
use VirtualSql\QueryParts\Element\VirtualSqlCondition;
use VirtualSql\QueryParts\Element\VirtualSqlConditionSet;
use VirtualSql\VirtualSqlConstant;

class VirtualSqlConditionSetBuilder
{
    /**
     * @param VirtualSqlColumn $column
     * @param $value
     * @param string $comparator
     * @return VirtualSqlCondition
     * @throws InvalidQueryPartException
     */
    public static function condition(VirtualSqlColumn $column, $value, string $comparator = VirtualSqlConstant::COMPARATOR_EQUALS): VirtualSqlCondition
    {
        return new VirtualSqlCondition($column, VirtualSqlConditionValue::factory($column, $value), $comparator);
    }

    /**
     * @param mixed ...$pieces
     * @return VirtualSqlConditionSet
     */
    public static function andX(...$pieces): VirtualSqlConditionSet
    {
        return self::createSet(VirtualSqlConstant::OPERATOR_AND, $pieces);
    }

    /**
     * @param mixed ...$pieces
     * @return VirtualSqlConditionSet
     */
   public static function orX(...$pieces): VirtualSqlConditionSet
    {
        return self::createSet(VirtualSqlConstant::OPERATOR_OR, $pieces);
    }

    /**
     * @param string $operator
     * @param VirtualSqlCondition[]|VirtualSqlConditionSet[] $pieces
     * @return VirtualSqlConditionSet
     */
    private static function createSet(string $operator, array $pieces): VirtualSqlConditionSet
    {
        return new VirtualSqlConditionSet($operator, $pieces);
    }
}
