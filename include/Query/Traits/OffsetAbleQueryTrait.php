<?php

namespace VirtualSql\Query\Traits;

use VirtualSql\Query\VirtualSqlQuery;

trait OffsetAbleQueryTrait
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
	 * @return VirtualSqlQuery
	 */
	public function setOffset(?int $offset): VirtualSqlQuery
	{
		$this->offset = $offset;
		return $this;
	}
}
