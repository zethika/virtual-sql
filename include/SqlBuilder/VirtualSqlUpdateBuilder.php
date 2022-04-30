<?php

namespace VirtualSql\SqlBuilder;

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


		return $string;
	}
}
