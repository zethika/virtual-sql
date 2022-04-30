<?php

namespace VirtualSql\Query;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;

class VirtualSqlInsertQuery extends VirtualSqlQuery
{
	/**
	 * @var VirtualSqlColumn[]
	 */
	protected $columns = [];

	/**
	 * @var array[]
	 */
	protected $valueSets = [];

	// On insert update
	public function __construct(VirtualSqlTable $table, array $config)
	{
		parent::__construct($table,$config);
	}

	/**
	 * @return VirtualSqlColumn[]
	 */
	public function getColumns(): array
	{
		return $this->columns;
	}

	/**
	 * @param VirtualSqlColumn[] $columns
	 */
	public function setColumns(array $columns): void
	{
		$this->columns = $columns;
	}

	/**
	 * @return array[]
	 */
	public function getValueSets(): array
	{
		return $this->valueSets;
	}

	/**
	 * @param array[] $valueSets
	 */
	public function setValueSets(array $valueSets): void
	{
		$this->valueSets = $valueSets;
	}


}
