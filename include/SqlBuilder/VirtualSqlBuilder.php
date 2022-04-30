<?php

namespace VirtualSql\SqlBuilder;

use JetBrains\PhpStorm\Pure;
use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\QueryParts\Element\ConditionValue\VirtualSqlArrayConditionValue;
use VirtualSql\QueryParts\Element\ConditionValue\VirtualSqlBetweenConditionValue;
use VirtualSql\QueryParts\Element\VirtualSqlCondition;
use VirtualSql\QueryParts\Element\VirtualSqlConditionSet;
use VirtualSql\VirtualSqlConstant;

/**
 * VirtualSqlBuilder and its child classes are responsible for taking a VirtualSqlQuery and building its corresponding SQL statement,
 * making an array of named parameters that represents the found values available in the meantime.
 */
abstract class VirtualSqlBuilder
{
	/**
	 * @var array
	 */
	protected array $namedParameters = [];


	abstract protected function getQuery(): VirtualSqlQuery;

	/**
	 * @return array
	 */
	public function getNamedParameters(): array
	{
		return $this->namedParameters;
	}

	/**
	 * @return string
	 */
	abstract public function getSql(): string;

	/**
	 * Builds the sql string representing a condition set, and adds its values to the variables array
	 *
	 * @param VirtualSqlConditionSet $conditionSet
	 * @return string
	 * @throws InvalidQueryPartException
	 */
	protected function buildConditionSetString(VirtualSqlConditionSet $conditionSet): string
	{
		$parts = [];
		foreach ($conditionSet->getConditions() as $condition)
		{
			if($condition instanceof VirtualSqlCondition)
			{
				$parts[] = $this->buildConditionString($condition);
			}
			else if($condition instanceof VirtualSqlConditionSet)
			{
				$parts[] = '('.$this->buildConditionSetString($condition).')';
			}
		}

		return implode(' '.$conditionSet->getOperator().' ',$parts);
	}

	/**
	 *
	 * @throws InvalidQueryPartException
	 */
	private function buildConditionString(VirtualSqlCondition $condition): string
	{
		$string = $this->getTableAliasedColumnString($condition->getColumn()).' ';

		$string .= match ($condition->getComparator())
		{
			VirtualSqlConstant::COMPARATOR_IN, VirtualSqlConstant::COMPARATOR_NOT_IN => $this->buildInNotInConditionString($condition),
			VirtualSqlConstant::COMPARATOR_BETWEEN => $this->buildBetweenConditionString($condition),
			default => $this->buildDefaultConditionString($condition),
		};

		return $string;
	}

	/**
	 * Builds the standard form of the comparator string, with no regard for special cases
	 *
	 * @param VirtualSqlCondition $condition
	 * @return string
	 */
	private function buildDefaultConditionString(VirtualSqlCondition $condition): string
	{
		return $condition->getComparator().' '.$this->addNamedParameter($condition->getValue()->getValue());
	}

	/**
	 * @param VirtualSqlCondition $condition
	 * @return string
	 * @throws InvalidQueryPartException
	 */
	private function buildBetweenConditionString(VirtualSqlCondition $condition): string
	{
		$value = $condition->getValue();
		if(!$value instanceof VirtualSqlBetweenConditionValue)
			throw new InvalidQueryPartException('Comparator for column "'.$condition->getColumn()->getColumn().'" was set as BETWEEN but was not provided a VirtualSqlBetweenConditionValue as value');

		$string = 'BETWEEN ';
		$string .= ($value->getStart() instanceof VirtualSqlColumn) ? $this->getTableAliasedColumnString($value->getStart()) : $this->addNamedParameter($value->getStart());
		$string .= ' AND ';
		$string .= ($value->getEnd() instanceof VirtualSqlColumn) ? $this->getTableAliasedColumnString($value->getEnd()) : $this->addNamedParameter($value->getEnd());

		return $string;
	}

	/**
	 * Checks the value type, and if it is an array type, loops over each element in the array, generating a named parameter for each and constructing the SQL string for them.
	 *
	 * @param VirtualSqlCondition $condition
	 * @return string
	 * @throws InvalidQueryPartException
	 */
	private function buildInNotInConditionString(VirtualSqlCondition $condition): string
	{
		$value = $condition->getValue();
		if(!$value instanceof VirtualSqlArrayConditionValue)
			throw new InvalidQueryPartException('Comparator for column "'.$condition->getColumn()->getColumn().'" was set as IN / NOT IN but was not provided a VirtualSqlArrayConditionValue as value');

		$parts = array_map(fn($single) => $this->addNamedParameter($single),$value->getArray());
		return $condition->getComparator().' ('.implode(',',$parts).')';
	}

	/**
	 * Returns the table name with applied alias, if any
	 *
	 * @param VirtualSqlTable $table
	 * @return string
	 */
	#[Pure] protected function getAliasedTableName(VirtualSqlTable $table): string
	{
		return $table->getAlias() !== null ? $table->getName().' as '.$table->getAlias() : $table->getName();
	}

	/**
	 * Returns the potentially aliased string representing a single column
	 *
	 * @param VirtualSqlColumn $column
	 * @return string
	 */
	#[Pure] protected function getTableAliasedColumnString(VirtualSqlColumn $column): string
	{
		$tableAlias = $column->getTable() instanceof VirtualSqlTable ? $column->getTable()->getAlias() : null;
		return $tableAlias === null ? $column->getColumn() : $tableAlias.'.'.$column->getColumn();
	}

	/**
	 * Returns the fully aliased column name, with both the table alias and the custom select alias (if any)
	 *
	 * @param VirtualSqlColumn $column
	 * @return string
	 */
	#[Pure] protected function getFullyAliasedColumnString(VirtualSqlColumn $column): string
	{
		$base = $this->getTableAliasedColumnString($column);
		return $column->getAlias() === null ? $base : $base.' as '.$column->getAlias();
	}

	/**
	 * @param $value
	 * @return string
	 */
	protected function addNamedParameter($value): string
	{
		$name = $this->getUnusedNamedParameter();
		$this->namedParameters[$name] = $value;
		return $name;
	}

	/**
	 * Returns a string representing the next available SQL named parameter
	 */
	#[Pure] private function getUnusedNamedParameter(): string
	{
		return ':v'.count($this->namedParameters);
	}
}
