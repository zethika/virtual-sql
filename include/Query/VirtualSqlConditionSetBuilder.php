<?php

namespace VirtualSql\Query;

use JetBrains\PhpStorm\Pure;
use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\Element\ConditionValue\VirtualSqlConditionValue;
use VirtualSql\Query\Element\VirtualSqlCondition;
use VirtualSql\Query\Element\VirtualSqlConditionSet;
use VirtualSql\VirtualSql;

class VirtualSqlConditionSetBuilder
{
	/**
	 * @param VirtualSqlColumn $column
	 * @param $value
	 * @param string $comparator
	 * @return VirtualSqlCondition
	 * @throws InvalidQueryPartException
	 */
	public static function condition(VirtualSqlColumn $column, $value, string $comparator = VirtualSql::COMPARATOR_EQUALS): VirtualSqlCondition
	{
		return new VirtualSqlCondition($column,VirtualSqlConditionValue::factory($column,$value),$comparator);
	}

	/**
	 * @param mixed ...$pieces
	 * @return VirtualSqlConditionSet
	 */
	#[Pure] public static function andX(...$pieces): VirtualSqlConditionSet
	{
		return self::createSet(VirtualSql::OPERATOR_AND,$pieces);
	}

	/**
	 * @param mixed ...$pieces
	 * @return VirtualSqlConditionSet
	 */
	#[Pure] public static function orX(...$pieces): VirtualSqlConditionSet
	{
		return self::createSet(VirtualSql::OPERATOR_OR,$pieces);
	}

	/**
	 * @param string $operator
	 * @param VirtualSqlCondition[]|VirtualSqlConditionSet[] $pieces
	 * @return VirtualSqlConditionSet
	 */
	#[Pure] private static function createSet(string $operator, array $pieces): VirtualSqlConditionSet
	{
		return new VirtualSqlConditionSet($operator, $pieces);
	}
}
