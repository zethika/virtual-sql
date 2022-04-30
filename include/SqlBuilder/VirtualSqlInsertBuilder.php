<?php

namespace VirtualSql\SqlBuilder;

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
		$string = 'INSERT INTO '.$this->getAliasedTableName($this->getQuery()->getBaseTable());


		return $string;
	}
}
