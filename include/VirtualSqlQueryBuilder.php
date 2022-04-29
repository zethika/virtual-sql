<?php

namespace VirtualSql;

use JetBrains\PhpStorm\Pure;
use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Exceptions\UndefinedQueryPartException;
use VirtualSql\Query\VirtualSqlSelectQuery;
use VirtualSql\QueryParts\Element\VirtualSqlCondition;
use VirtualSql\QueryParts\Element\VirtualSqlConditionSet;
use VirtualSql\QueryParts\Element\VirtualSqlJoin;

class VirtualSqlQueryBuilder
{
	const TYPE_SELECT = 0;
	const TYPE_INSERT = 1;
	const TYPE_UPDATE = 2;
	const TYPE_DELETE = 3;

	const TYPE_CLASS_MAP = [
		self::TYPE_SELECT => VirtualSqlSelectQuery::class
	];

	/**
	 * @param VirtualSqlTable $baseTable
	 * @param int $type
	 * @return VirtualSqlQueryBuilder
	 */
	public static function factory(VirtualSqlTable $baseTable, int $type = self::TYPE_SELECT): VirtualSqlQueryBuilder
	{
		$query = new VirtualSqlSelectQuery($baseTable);
		return new self($query,[$baseTable]);
	}

	/**
	 * @var VirtualSqlSelectQuery
	 */
	private VirtualSqlSelectQuery $query;

	/**
	 * A map of the tables known to the query builder.
	 * The key is the table alias for easier referencing
	 *
	 * @var VirtualSqlTable[]
	 */
	private array $tables = [];

	/**
	 * @param \VirtualSql\Query\VirtualSqlSelectQuery $query
	 * @param VirtualSqlTable[] $tables
	 */
	public function __construct(VirtualSqlSelectQuery $query, array $tables)
	{
		$this->query = $query;
		foreach ($tables as $table){
			$this->ensureTable($table);
		}
	}

	/**
	 *
	 */
	public function addSelect(VirtualSqlColumn $column)
	{

	}

	/**
	 * @param VirtualSqlTable $table
	 * @param string $column
	 * @return VirtualSqlColumn
	 * @throws UndefinedQueryPartException
	 */
	public function getTableColumn(VirtualSqlTable $table, string $column): VirtualSqlColumn
	{
		$columnInstance = null;
		if($table->getAlias() !== null)
		{
			if(isset($this->tables[$table->getAlias()]) !== false)
				$columnInstance = $this->tables[$table->getAlias()]->getColumn($column);
		}
		else
		{
			foreach ($this->getTables() as $definedTable)
			{
				if($table->getName() === $definedTable->getName())
					$columnInstance = $definedTable->getColumn($column);
			}
		}

		if($columnInstance === null)
			throw new UndefinedQueryPartException('Column "'.$column.'" does not exist on table "'.$table->getName().'" or the table has not been added to the builder.');

		return $columnInstance;
	}

	/**
	 * @return VirtualSqlSelectQuery
	 */
	public function getQuery(): VirtualSqlSelectQuery
	{
		return $this->query;
	}

	/**
	 * @return VirtualSqlTable[]
	 */
	public function getTables(): array
	{
		return $this->tables;
	}

	/**
	 * Adds a given table to the known tables map for the query builder
	 * This is private, since it should never be used directly from outside but rather only indirectly via adding joins and via the initial tables
	 */
	private function ensureTable(VirtualSqlTable $table): void
	{
		$this->ensureAlias($table);

		if(isset($this->tables[$table->getAlias()]))
			return;

		$this->tables[$table->getAlias()] = $table;
	}

	/**
	 * Ensures that the given table has a unique alias in the local map
	 */
	private function ensureAlias(VirtualSqlTable $table): void
	{
		if($table->getAlias() !== null)
			return;

		$i = count($this->tables);
		do{
			$table->setAlias(self::generateTableAlias($i));
			$i++;
		} while(isset($this->tables[$table->getAlias()]));
	}

	/**
	 * @param int $number
	 * @return string
	 */
	public static function generateTableAlias(int $number): string
	{
		return '_t'.$number;
	}


	/**
	 * @param VirtualSqlColumn $from
	 * @param VirtualSqlColumn $to
	 * @param VirtualSqlConditionSet|null $conditions
	 * @return VirtualSqlQueryBuilder
	 * @throws InvalidQueryPartException
	 */
	public function leftJoin(VirtualSqlColumn $from, VirtualSqlColumn $to, ?VirtualSqlConditionSet $conditions = null): VirtualSqlQueryBuilder
	{
		return $this->join(VirtualSql::JOIN_TYPE_LEFT,...func_get_args());
	}

	/**
	 * @param VirtualSqlColumn $from
	 * @param VirtualSqlColumn $to
	 * @param VirtualSqlConditionSet|null $conditions
	 * @return VirtualSqlQueryBuilder
	 * @throws InvalidQueryPartException
	 */
	public function rightJoin(VirtualSqlColumn $from, VirtualSqlColumn $to, ?VirtualSqlConditionSet $conditions = null): VirtualSqlQueryBuilder
	{
		return $this->join(VirtualSql::JOIN_TYPE_RIGHT,...func_get_args());
	}

	/**
	 * @param VirtualSqlColumn $from
	 * @param VirtualSqlColumn $to
	 * @param VirtualSqlConditionSet|null $conditions
	 * @return VirtualSqlQueryBuilder
	 * @throws InvalidQueryPartException
	 */
	public function outerJoin(VirtualSqlColumn $from, VirtualSqlColumn $to, ?VirtualSqlConditionSet $conditions = null): VirtualSqlQueryBuilder
	{
		return $this->join(VirtualSql::JOIN_TYPE_OUTER,...func_get_args());
	}

	/**
	 * @param VirtualSqlColumn $from
	 * @param VirtualSqlColumn $to
	 * @param VirtualSqlConditionSet|null $conditions
	 * @return VirtualSqlQueryBuilder
	 * @throws InvalidQueryPartException
	 */
	public function innerJoin(VirtualSqlColumn $from, VirtualSqlColumn $to, ?VirtualSqlConditionSet $conditions = null): VirtualSqlQueryBuilder
	{
		return $this->join(VirtualSql::JOIN_TYPE_INNER,...func_get_args());
	}

	/**
	 * @param string $joinType
	 * @param VirtualSqlColumn $from
	 * @param VirtualSqlColumn $to
	 * @param VirtualSqlConditionSet|null $conditions
	 * @return VirtualSqlQueryBuilder
	 * @throws InvalidQueryPartException
	 */
	public function join(string $joinType, VirtualSqlColumn $from, VirtualSqlColumn $to, ?VirtualSqlConditionSet $conditions = null): VirtualSqlQueryBuilder
	{
		return $this->addJoinToQuery(new VirtualSqlJoin(...func_get_args()));
	}


	/**
	 * Adds a join to the query, while ensuring that the tables related to the join have been added to the local tables map
	 *
	 * @param VirtualSqlJoin $join
	 * @param bool $allowDuplicates
	 * @return $this
	 */
	public function addJoinToQuery(VirtualSqlJoin $join, bool $allowDuplicates = true): VirtualSqlQueryBuilder
	{
		$fromTable = $join->getFromColumn()->getTable();
		if($fromTable->getAlias() === null || !isset($this->tables[$fromTable->getAlias()]))
			$this->ensureTable($fromTable);

		$toTable = $join->getToColumn()->getTable();
		if($toTable->getAlias() === null || !isset($this->tables[$toTable->getAlias()]))
			$this->ensureTable($toTable);

		$this->query->addJoin($join,$allowDuplicates);

		return $this;
	}

	/**
	 * @param VirtualSqlCondition|VirtualSqlConditionSet $condition
	 */
	public function addWhere(VirtualSqlConditionSet|VirtualSqlCondition $condition)
	{
		$this->query->addWhere($condition);
	}

	/**
	 * @return string
	 * @throws InvalidQueryPartException
	 */
	public function getSql(): string
	{
		return $this->query->getSql();
	}

	/**
	 * @return array
	 */
	#[Pure] public function getNamedParameters(): array
	{
		return $this->query->getNamedParameters();
	}
}
