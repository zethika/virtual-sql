<?php

namespace VirtualSql\Query\Types;

use VirtualSql\Definition\VirtualSqlTable;

abstract class LimitAbleSqlQuery extends WhereAbleSqlQuery
{
	/**
	 * @var int|null
	 */
	protected int|null $limit;

	/**
	 * @param VirtualSqlTable $baseTable
	 * @param array $config
	 */
	public function __construct(VirtualSqlTable $baseTable, array $config)
	{
		$this->limit = isset($config['limit']) && is_integer($config['limit']) ? (int)$config['limit'] : null;
		parent::__construct($baseTable,$config);
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
	 * @return LimitAbleSqlQuery
	 */
	public function setLimit(?int $limit): LimitAbleSqlQuery
	{
		$this->limit = $limit;
		return $this;
	}
}
