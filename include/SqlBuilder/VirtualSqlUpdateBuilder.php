<?php

namespace VirtualSql\SqlBuilder;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\Query\VirtualSqlUpdateQuery;
use VirtualSql\SqlBuilder\Partials\WhereAbleSqlBuilder;

class VirtualSqlUpdateBuilder extends WhereAbleSqlBuilder
{
	/**
	 * @var VirtualSqlUpdateQuery
	 */
	private VirtualSqlUpdateQuery $query;

	/**
	 * @param VirtualSqlUpdateQuery $query
	 */
	public function __construct(VirtualSqlUpdateQuery $query)
	{
		$this->query = $query;
	}

	/**
	 * @return VirtualSqlUpdateQuery
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
		$string = 'UPDATE '.$this->getAliasedTableName($this->getQuery()->getBaseTable());

		$values = $this->getQuery()->getValues();
		$parts = array_map(fn(VirtualSqlColumn $column) => $column->getColumn().' = '.$this->parseAddValue($column, $values[$column->getColumn()] ?? null), $this->getQuery()->getColumns());
		$string .= ' SET '.implode(', ',$parts);

		$parts = [
			$this->buildJoinString($this->getQuery()->getJoins()),
			$this->buildWhereString($this->getQuery()->getWhere()),
		];

		foreach ($parts as $part)
		{
			if($part !== null)
				$string .= ' '.$part;
		}

		return $string;
	}
}
