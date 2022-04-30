<?php

namespace VirtualSql\Query\Types;

use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\QueryParts\Element\VirtualSqlCondition;
use VirtualSql\QueryParts\Element\VirtualSqlConditionSet;

abstract class WhereAbleSqlQuery extends JoinAbleSqlQuery
{
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
