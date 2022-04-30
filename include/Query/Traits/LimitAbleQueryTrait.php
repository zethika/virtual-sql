<?php

namespace VirtualSql\Query\Traits;

use VirtualSql\Query\VirtualSqlQuery;

trait LimitAbleQueryTrait
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
	 * @return VirtualSqlQuery
	 */
	public function setLimit(?int $limit): VirtualSqlQuery
	{
		$this->limit = $limit;
		return $this;
	}
}
