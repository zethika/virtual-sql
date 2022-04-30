<?php

namespace VirtualSql\Query\Types;

use VirtualSql\Query\VirtualSqlQuery;

abstract class LimitAbleSqlQuery extends WhereAbleSqlQuery
{
	/**
	 * @var int|null
	 */
	private int|null $limit;

	/**
	 * @return int|null
	 */
	public function getLimit(): ?int
	{
		return $this->limit;
	}

	/**
	 * @param int|null $limit
	 * @return LimitAbleSqlQuery
	 */
	public function setLimit(?int $limit): LimitAbleSqlQuery
	{
		$this->limit = $limit;
		return $this;
	}
}
