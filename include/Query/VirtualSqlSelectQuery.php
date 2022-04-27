<?php

namespace VirtualSql\Query;

use JetBrains\PhpStorm\Pure;
use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\Element\VirtualSqlCondition;
use VirtualSql\Query\Element\VirtualSqlConditionSet;
use VirtualSql\Query\Element\VirtualSqlJoin;
use VirtualSql\VirtualSql;

class VirtualSqlSelectQuery
{
	/**
	 * @var VirtualSqlColumn[]
	 */
	private array $selects;

	/**
	 * @var VirtualSqlTable
	 */
	private VirtualSqlTable $from;

	/**
	 * @var VirtualSqlJoin[]
	 */
	private array $joins;

	/**
	 * @var VirtualSqlConditionSet
	 */
	private VirtualSqlConditionSet $where;

	/**
	 * @var int|null
	 */
	private int|null $offset;

	/**
	 * @var int|null
	 */
	private int|null $limit;

	/**
	 * @var VirtualSqlQuerySqlBuilder
	 */
	private VirtualSqlQuerySqlBuilder $sqlBuilder;

	/**
	 * @param VirtualSqlTable $from
	 * @param VirtualSqlJoin[] $joins
	 * @param VirtualSqlConditionSet|null $where
	 * @param VirtualSqlColumn[] $selects
	 */
	public function __construct(VirtualSqlTable $from, array $joins = [], ?VirtualSqlConditionSet $where = null, array $selects = [])
	{
		$this->from = $from;
		$this->joins = $joins;
		$this->where = $where === null ? new VirtualSqlConditionSet(VirtualSql::OPERATOR_AND) : $where;
		$this->selects = $selects;
		$this->sqlBuilder = new VirtualSqlQuerySqlBuilder($this);
	}

	/**
	 * @return int|null
	 */
	public function getOffset(): ?int
	{
		return $this->offset;
	}

	/**
	 * @param int|null $offset
	 * @return VirtualSqlSelectQuery
	 */
	public function setOffset(?int $offset): VirtualSqlSelectQuery
	{
		$this->offset = $offset;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getLimit(): ?int
	{
		return $this->limit;
	}

	/**
	 * @param int|null $limit
	 * @return VirtualSqlSelectQuery
	 */
	public function setLimit(?int $limit): VirtualSqlSelectQuery
	{
		$this->limit = $limit;
		return $this;
	}

	/**
	 * @return VirtualSqlTable
	 */
	public function getFrom(): VirtualSqlTable
	{
		return $this->from;
	}

	/**
	 * @return VirtualSqlJoin[]
	 */
	public function getJoins(): array
	{
		return $this->joins;
	}

	/**
	 * @param VirtualSqlJoin[] $joins
	 * @return VirtualSqlSelectQuery
	 */
	public function setJoins(array $joins): VirtualSqlSelectQuery
	{
		$this->joins = $joins;
		return $this;
	}

	/**
	 * @return VirtualSqlConditionSet
	 */
	public function getWhere(): VirtualSqlConditionSet
	{
		return $this->where;
	}

	/**
	 * @param VirtualSqlConditionSet $where
	 * @return VirtualSqlSelectQuery
	 */
	public function setWhere(VirtualSqlConditionSet $where): VirtualSqlSelectQuery
	{
		$this->where = $where;
		return $this;
	}


	/**
	 * @param VirtualSqlJoin $join
	 * @param bool $allowDuplicates
	 * @return VirtualSqlSelectQuery
	 */
	public function addJoin(VirtualSqlJoin $join, bool $allowDuplicates = true): VirtualSqlSelectQuery
	{
		if($allowDuplicates === false)
		{
			foreach ($this->getJoins() as $existingJoin)
			{
				if($existingJoin->getToColumn()->getTable()->getName() !== $join->getToColumn()->getTable()->getName())
					continue;

				if($existingJoin->getFromColumn()->getTable()->getName() !== $join->getFromColumn()->getTable()->getName())
					continue;

				return $this;
			}
		}

		$this->joins[] = $join;

		return $this;
	}


	/**
	 * @param VirtualSqlCondition|VirtualSqlConditionSet $where
	 * @return $this
	 */
	public function addWhere(VirtualSqlConditionSet|VirtualSqlCondition $where): VirtualSqlSelectQuery
	{
		$this->where->addCondition($where);
		return $this;
	}

	/**
	 * @return VirtualSqlColumn[]
	 */
	public function getSelects(): array
	{
		return $this->selects;
	}

	/**
	 * @param VirtualSqlColumn[] $selects
	 * @return VirtualSqlSelectQuery
	 */
	public function setSelects(array $selects): VirtualSqlSelectQuery
	{
		$this->selects = $selects;
		return $this;
	}

	/**
	 * @param VirtualSqlColumn $columnSelect
	 * @return VirtualSqlSelectQuery
	 */
	public function addSelect(VirtualSqlColumn $columnSelect): VirtualSqlSelectQuery
	{
		$this->selects[] = $columnSelect;
		return $this;
	}

	/**
	 * @return string
	 * @throws InvalidQueryPartException
	 */
	public function getSql(): string
	{
		return $this->sqlBuilder->getSql();
	}

	/**
	 *
	 */
	#[Pure] public function getNamedParameters(): array
	{
		return $this->sqlBuilder->getNamedParameters();
	}
}
