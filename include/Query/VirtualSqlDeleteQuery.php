<?php

namespace VirtualSql\Query;

use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Query\Partials\LimitAbleSqlQuery;
use VirtualSql\SqlBuilder\VirtualSqlDeleteBuilder;

class VirtualSqlDeleteQuery extends LimitAbleSqlQuery
{
	/**
	 * @param VirtualSqlTable $table
	 * @param array $config
	 */
	public function __construct(VirtualSqlTable $table, array $config)
	{
		$this->builder = new VirtualSqlDeleteBuilder($this);
		parent::__construct($table, $config);
	}
}
