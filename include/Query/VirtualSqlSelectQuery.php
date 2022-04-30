<?php

namespace VirtualSql\Query;

use JetBrains\PhpStorm\Pure;
use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Query\SqlBuilder\VirtualSqlQuerySqlBuilder;
use VirtualSql\Query\Traits\JoinAbleQueryTrait;
use VirtualSql\Query\Traits\LimitAbleQueryTrait;
use VirtualSql\Query\Traits\OffsetAbleQueryTrait;
use VirtualSql\Query\Traits\WhereAbleQueryTrait;
use VirtualSql\QueryParts\Element\VirtualSqlConditionSet;
use VirtualSql\QueryParts\Element\VirtualSqlJoin;
use VirtualSql\VirtualSql;

class VirtualSqlSelectQuery extends VirtualSqlQuery
{
	use WhereAbleQueryTrait;
	use JoinAbleQueryTrait;
	use LimitAbleQueryTrait;
	use OffsetAbleQueryTrait;

	/**
	 * @var VirtualSqlColumn[]
	 */
	private array $selects;

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
		$this->joins = $joins;
		$this->where = $where === null ? new VirtualSqlConditionSet(VirtualSql::OPERATOR_AND) : $where;
		$this->selects = $selects;
		parent::__construct($from);
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
	 *
	 */
	#[Pure] public function getNamedParameters(): array
	{
		return $this->sqlBuilder->getNamedParameters();
	}
}
