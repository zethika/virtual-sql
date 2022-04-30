<?php

namespace VirtualSql\Query\Traits;

use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\QueryParts\Element\VirtualSqlJoin;

trait JoinAbleQueryTrait
{

	/**
	 * @return VirtualSqlJoin[]
	 */
	public function getJoins(): array
	{
		return $this->joins;
	}

	/**
	 * @param VirtualSqlJoin[] $joins
	 * @return VirtualSqlQuery
	 */
	public function setJoins(array $joins): VirtualSqlQuery
	{
		$this->joins = $joins;
		return $this;
	}

	/**
	 * @param VirtualSqlJoin $join
	 * @param bool $allowDuplicates
	 * @return VirtualSqlQuery
	 */
	public function addJoin(VirtualSqlJoin $join, bool $allowDuplicates = true): VirtualSqlQuery
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
}
