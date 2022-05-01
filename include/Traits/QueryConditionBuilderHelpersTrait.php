<?php

namespace VirtualSql\Traits;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\QueryParts\Element\VirtualSqlCondition;
use VirtualSql\QueryParts\Element\VirtualSqlConditionSet;
use VirtualSql\QueryParts\VirtualSqlConditionSetBuilder;
use VirtualSql\VirtualSqlConstant;

/**
 * Trait to simplify communication with VirtualSqlConditionSetBuilder directly from a query instance, rather than having to use a tertiary class.
 */
trait QueryConditionBuilderHelpersTrait
{
    /**
     * @param VirtualSqlColumn $column
     * @param $value
     * @param string $comparator
     * @return VirtualSqlCondition
     * @throws InvalidQueryPartException
     */
    public function condition(VirtualSqlColumn $column, $value, string $comparator = VirtualSqlConstant::COMPARATOR_EQUALS): VirtualSqlCondition
    {
        return VirtualSqlConditionSetBuilder::condition(...func_get_args());
    }

    /**
     * @param mixed ...$pieces
     * @return VirtualSqlConditionSet
     */
    public function andX(...$pieces): VirtualSqlConditionSet
    {
        return VirtualSqlConditionSetBuilder::andX(...$pieces);
    }

    /**
     * @param mixed ...$pieces
     * @return VirtualSqlConditionSet
     */
    public function orX(...$pieces): VirtualSqlConditionSet
    {
        return VirtualSqlConditionSetBuilder::orX(...$pieces);
    }
}
