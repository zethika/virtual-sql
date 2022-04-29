<?php

namespace VirtualSql\Query;

use JetBrains\PhpStorm\Pure;
use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\QueryParts\Element\ConditionValue\VirtualSqlArrayConditionValue;
use VirtualSql\QueryParts\Element\ConditionValue\VirtualSqlBetweenConditionValue;
use VirtualSql\QueryParts\Element\VirtualSqlCondition;
use VirtualSql\QueryParts\Element\VirtualSqlConditionSet;
use VirtualSql\VirtualSql;

class VirtualSqlQuerySqlBuilder
{
	/**
	 * @var VirtualSqlSelectQuery
	 */
	private VirtualSqlSelectQuery $query;

	/**
	 * @var string[]
	 */
	private array $selectParts = [];

	/**
	 * @var string[]
	 */
	private array $joinParts = [];

	/**
	 * @var string
	 */
	private string $where = '';

	/**
	 * @var array
	 */
	private array $namedParameters = [];

	/**
	 * @param VirtualSqlSelectQuery $query
	 */
	public function __construct(VirtualSqlSelectQuery $query)
	{
		$this->query = $query;
	}

	/**
	 * @return array
	 */
	public function getNamedParameters(): array
	{
		return $this->namedParameters;
	}

	/**
	 *
	 * @throws InvalidQueryPartException
	 */
	public function getSql(): string
	{
		$this->populateSelects();
		$this->populateJoins();
		$this->populateWhere();

		return $this->buildString();
	}

	/**
	 *
	 */
	#[Pure] private function buildString(): string
	{
		$string = 'SELECT '.implode(',',$this->selectParts).' FROM '.$this->getAliasedTableName($this->query->getBaseTable());

		if(count($this->joinParts) > 0)
			$string .= ' '.implode(' ',$this->joinParts);

		if(strlen($this->where) > 0)
			$string .= ' WHERE '.$this->where;

		if($this->query->getLimit() !== null)
			$string .= ' LIMIT '.$this->query->getLimit();

		if($this->query->getOffset() !== null)
			$string .= ' OFFSET '.$this->query->getOffset();

		return $string;
	}

	/**
	 * Populates the where portion of the query
	 * @throws InvalidQueryPartException
	 */
	private function populateWhere()
	{
		if(count($this->query->getWhere()->getConditions()) !== 0)
			$this->where = $this->buildConditionSetString($this->query->getWhere());
	}

	/**
	 * Populates the join portion of the query
	 * @throws InvalidQueryPartException
	 */
	private function populateJoins()
	{
		foreach ($this->query->getJoins() as $join)
		{
			$string = $join->getType().' JOIN '.$this->getAliasedTableName($join->getToColumn()->getTable()).' ON ('.$this->getTableAliasedColumnString($join->getFromColumn()).' = '.$this->getTableAliasedColumnString($join->getToColumn());

			if($join->getConditionSet() !== null)
				$string .= ' AND '.$this->buildConditionSetString($join->getConditionSet());

			$this->joinParts[] = $string.')';
		}
	}

	/**
	 * Populates the select portion of the query
	 */
	private function populateSelects()
	{
		if(count($this->query->getSelects()) === 0)
		{
			$this->selectParts[] = VirtualSql::KEYWORD_WILDCARD;
			return;
		}

		$this->selectParts = array_map(fn(VirtualSqlColumn $column) => $this->getFullyAliasedColumnString($column), $this->query->getSelects());
	}

	/**
	 * Builds the sql string representing a condition set, and adds its values to the variables array
	 *
	 * @param VirtualSqlConditionSet $conditionSet
	 * @return string
	 * @throws InvalidQueryPartException
	 */
	private function buildConditionSetString(VirtualSqlConditionSet $conditionSet): string
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
			VirtualSql::COMPARATOR_IN, VirtualSql::COMPARATOR_NOT_IN => $this->buildInNotInConditionString($condition),
			VirtualSql::COMPARATOR_BETWEEN => $this->buildBetweenConditionString($condition),
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
	#[Pure] private function getAliasedTableName(VirtualSqlTable $table): string
	{
		return $table->getAlias() !== null ? $table->getName().' as '.$table->getAlias() : $table->getName();
	}

	/**
	 * Returns the potentially aliased string representing a single column
	 *
	 * @param VirtualSqlColumn $column
	 * @return string
	 */
	#[Pure] private function getTableAliasedColumnString(VirtualSqlColumn $column): string
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
	#[Pure] private function getFullyAliasedColumnString(VirtualSqlColumn $column): string
	{
		$base = $this->getTableAliasedColumnString($column);
		return $column->getAlias() === null ? $base : $base.' as '.$column->getAlias();
	}

	/**
	 * @param $value
	 * @return string
	 */
	private function addNamedParameter($value): string
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
