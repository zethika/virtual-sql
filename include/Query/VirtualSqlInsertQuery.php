<?php

namespace VirtualSql\Query;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\SqlBuilder\VirtualSqlInsertBuilder;

class VirtualSqlInsertQuery extends VirtualSqlQuery
{
	/**
	 * @var VirtualSqlColumn[]
	 */
	protected array $columns = [];

	/**
	 * @var array[]
	 */
	protected array $valueSets = [];

	/**
	 * @var VirtualSqlColumn[]
	 */
	protected array $onDuplicateUpdateColumns = [];

	/**
	 * @param VirtualSqlTable $table
	 * @param array $config
	 */
	public function __construct(VirtualSqlTable $table, array $config)
	{
		$this->builder = new VirtualSqlInsertBuilder($this);
		$this->columns = isset($config['columns']) && is_array($config['columns']) ? array_values(array_filter($config['columns'],fn($column) => $column instanceof VirtualSqlColumn)) : [];
		$this->valueSets = isset($config['valueSets']) && is_array($config['valueSets']) ? $config['valueSets'] : [];
		$this->onDuplicateUpdateColumns = isset($config['onDuplicateUpdateColumns']) && is_array($config['onDuplicateUpdateColumns']) ? array_values(array_filter($config['onDuplicateUpdateColumns'],fn($column) => $column instanceof VirtualSqlColumn)) : [];
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

	/**
	 * @return VirtualSqlColumn[]
	 */
	public function getOnDuplicateUpdateColumns(): array
	{
		return $this->onDuplicateUpdateColumns;
	}

	/**
	 * @param VirtualSqlColumn[] $onDuplicateUpdateColumns
	 */
	public function setOnDuplicateUpdateColumns(array $onDuplicateUpdateColumns): void
	{
		$this->onDuplicateUpdateColumns = $onDuplicateUpdateColumns;
	}
}
