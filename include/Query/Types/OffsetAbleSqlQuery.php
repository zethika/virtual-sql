<?php

namespace VirtualSql\Query\Types;

use VirtualSql\Query\VirtualSqlQuery;

abstract class OffsetAbleSqlQuery extends LimitAbleSqlQuery
{
	/**
	 * @var int|null
	 */
	private int|null $offset;

	/**
	 * @return int|null
	 */
	public function getOffset(): ?int
	{
		return $this->offset;
	}

	/**
	 * @param int|null $offset
	 * @return OffsetAbleSqlQuery
	 */
	public function setOffset(?int $offset): OffsetAbleSqlQuery
	{
		$this->offset = $offset;
		return $this;
	}
}
