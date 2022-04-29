<?php

namespace VirtualSql\Query;

use VirtualSql\Definition\VirtualSqlTable;

abstract class VirtualSqlQuery
{
	/**
	 * @var VirtualSqlTable
	 */
	private VirtualSqlTable $baseTable;

	/**
	 * @param VirtualSqlTable $baseTable
	 */
	public function __construct(VirtualSqlTable $baseTable)
	{
		$this->baseTable = $baseTable;
	}

	/**
	 * @return VirtualSqlTable
	 */
	public function getBaseTable(): VirtualSqlTable
	{
		return $this->baseTable;
	}
}
