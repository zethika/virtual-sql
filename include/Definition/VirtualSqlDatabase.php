<?php

namespace VirtualSql\Definition;

use VirtualSql\Traits\SingletonTrait;

class VirtualSqlDatabase
{
	use SingletonTrait;

	/**
	 * @var VirtualSqlTable[]
	 */
	private array $tables = [];

	/**
	 * @param VirtualSqlTable[] $tables
	 */
	public function __construct(array $tables)
	{
		$this->tables = $tables;
	}

	/**
	 * @return VirtualSqlTable[]
	 */
	public function getTables(): array
	{
		return $this->tables;
	}
}
