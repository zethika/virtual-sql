<?php

namespace VirtualSql\SqlBuilder;

use JetBrains\PhpStorm\Pure;
use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\VirtualSqlInsertQuery;
use VirtualSql\Query\VirtualSqlQuery;

class VirtualSqlInsertBuilder extends VirtualSqlBuilder
{
	/**
	 * @var VirtualSqlInsertQuery
	 */
	private VirtualSqlInsertQuery $query;

	/**
	 * @param VirtualSqlInsertQuery $query
	 */
	public function __construct(VirtualSqlInsertQuery $query)
	{
		$this->query = $query;
	}

	/**
	 * @return VirtualSqlInsertQuery
	 */
	protected function getQuery(): VirtualSqlQuery
	{
		return $this->query;
	}

	/**
	 * @return string
	 * @throws InvalidQueryPartException
	 */
	public function getSql(): string
	{
		$string = 'INSERT INTO '.$this->getAliasedTableName($this->getQuery()->getBaseTable()). ' (';
		$string .= implode(',',array_map(fn(VirtualSqlColumn $column) => $column->getColumn(),$this->getQuery()->getColumns()));
		$string .= ') VALUES ';

		$sets = [];
		foreach ($this->getQuery()->getValueSets() as $set)
		{
			$parts = [];
			foreach ($this->getQuery()->getColumns() as $column)
			{
				$parts[] = $this->parseAddValue($column, $set[$column->getColumn()] ?? null);
			}
			$sets[] = '('.implode(',',$parts).')';
		}

		$string .= implode(',',$sets);

		return $string.$this->buildOnDuplicateKeyPart();
	}

	/**
	 * @param VirtualSqlColumn $column
	 * @param $value
	 * @return string
	 * @throws InvalidQueryPartException
	 */
	private function parseAddValue(VirtualSqlColumn $column, $value): string
	{
		if($value === null && $column->isNullable() === false)
			throw new InvalidQueryPartException('Column "'.$column->getColumn().'" must have a value');

		return $value === null ? 'NULL' : $this->addNamedParameter($value);
	}

	/**
	 * @return string
	 */
	#[Pure] private function buildOnDuplicateKeyPart(): string
	{
		$string = '';
		if(count($this->getQuery()->getOnDuplicateUpdateColumns()) !== 0)
		{
			$string .= ' ON DUPLICATE KEY UPDATE ';
			$parts = [];
			foreach ($this->getQuery()->getOnDuplicateUpdateColumns() as $column)
			{
				$parts[] = $column->getColumn().'=VALUES('.$column->getColumn().')';
			}

			$string .= implode(', ',$parts);
		}
		return $string;
	}
}
