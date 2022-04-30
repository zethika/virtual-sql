<?php

namespace VirtualSql\Query;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Query\Partials\WhereAbleSqlQuery;
use VirtualSql\SqlBuilder\VirtualSqlUpdateBuilder;

class VirtualSqlUpdateQuery extends WhereAbleSqlQuery
{
	/**
	 * @var VirtualSqlColumn[]
	 */
	protected array $columns = [];

	/**
	 * @var array
	 */
	protected array $values = [];

	/**
	 * @param VirtualSqlTable $table
	 * @param array $config
	 */
	public function __construct(VirtualSqlTable $table, array $config)
	{
		$this->builder = new VirtualSqlUpdateBuilder($this);
		$this->columns = isset($config['columns']) && is_array($config['columns']) ? array_values(array_filter($config['columns'], fn($column) => $column instanceof VirtualSqlColumn)) : [];
		$this->values = isset($config['values']) && is_array($config['values']) ? $config['values'] : [];
		parent::__construct($table, $config);
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
	 * @param VirtualSqlColumn $column
	 */
	public function addColumn(VirtualSqlColumn $column)
	{
		foreach ($this->columns as $known)
		{
			if($known->getColumn() === $column->getColumn() && $column->getTable()->getName() === $known->getTable()->getName())
				return;
		}

		$this->columns[] = $column;
	}

	/**
	 * @param VirtualSqlColumn $column
	 * @param $value
	 */
	public function addColumnWithValue(VirtualSqlColumn $column, $value)
	{
		$this->addColumn($column);
		$this->setColumnValue($column->getColumn(),$value);
	}

	/**
	 * @return array
	 */
	public function getValues(): array
	{
		return $this->values;
	}

	/**
	 * @param array $values
	 */
	public function setValues(array $values): void
	{
		$this->values = $values;
	}

	/**
	 * @param string $column
	 * @param $value
	 */
	public function setColumnValue(string $column, $value)
	{
		$this->values[$column] = $value;
	}
}
