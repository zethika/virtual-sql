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
	 * @param array $columns
	 * @return VirtualSqlUpdateQuery
	 */
	public function setColumns(array $columns): VirtualSqlUpdateQuery
	{
		$this->columns = $columns;
		return $this;
	}

	/**
	 * @param VirtualSqlColumn $column
	 * @return VirtualSqlUpdateQuery
	 */
	public function addColumn(VirtualSqlColumn $column): VirtualSqlUpdateQuery
	{
		foreach ($this->columns as $known)
		{
			if($known->getColumn() === $column->getColumn() && $column->getTable()->getName() === $known->getTable()->getName())
				return $this;
		}

		$this->columns[] = $column;

		return $this;
	}

	/**
	 * @param VirtualSqlColumn $column
	 * @param $value
	 * @return VirtualSqlUpdateQuery
	 */
	public function addColumnWithValue(VirtualSqlColumn $column, $value): VirtualSqlUpdateQuery
	{
		$this->addColumn($column);
		$this->setColumnValue($column->getColumn(),$value);
		return $this;
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
	 * @return VirtualSqlUpdateQuery
	 */
	public function setValues(array $values): VirtualSqlUpdateQuery
	{
		$this->values = $values;
		return $this;
	}

	/**
	 * @param string $column
	 * @param $value
	 * @return VirtualSqlUpdateQuery
	 */
	public function setColumnValue(string $column, $value): VirtualSqlUpdateQuery
	{
		$this->values[$column] = $value;
		return $this;
	}
}
