<?php

namespace VirtualSql\Query\Traits;

use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\QueryParts\Element\VirtualSqlCondition;
use VirtualSql\QueryParts\Element\VirtualSqlConditionSet;
use VirtualSql\QueryParts\Element\VirtualSqlJoin;

trait WhereAbleQueryTrait
{
	/**
	 * @var VirtualSqlJoin[]
	 */
	private array $joins;

	/**
	 * @var VirtualSqlConditionSet
	 */
	private VirtualSqlConditionSet $where;

	/**
	 * @return VirtualSqlConditionSet
	 */
	public function getWhere(): VirtualSqlConditionSet
	{
		return $this->where;
	}

	/**
	 * @param VirtualSqlConditionSet $where
	 * @return VirtualSqlQuery
	 */
	public function setWhere(VirtualSqlConditionSet $where): VirtualSqlQuery
	{
		$this->where = $where;
		return $this;
	}

	/**
	 * @param VirtualSqlCondition|VirtualSqlConditionSet $where
	 * @return VirtualSqlQuery
	 */
	public function addWhere(VirtualSqlConditionSet|VirtualSqlCondition $where): VirtualSqlQuery
	{
		$this->where->addCondition($where);
		return $this;
	}
}
