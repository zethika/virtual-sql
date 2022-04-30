<?php

namespace VirtualSql\SqlBuilder\Partials;

use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\QueryParts\Element\VirtualSqlJoin;
use VirtualSql\SqlBuilder\VirtualSqlBuilder;

abstract class JoinAbleSqlBuilder extends VirtualSqlBuilder
{
	/**
	 * @param VirtualSqlJoin[] $joins
	 * @return string|null
	 * @throws InvalidQueryPartException
	 */
	protected function buildJoinString(array $joins): ?string
	{
		$parts = [];
		foreach ($joins as $join)
		{
			$string = $join->getType().' JOIN '.$this->getAliasedTableName($join->getToColumn()->getTable()).' ON ('.$this->getTableAliasedColumnString($join->getFromColumn()).' = '.$this->getTableAliasedColumnString($join->getToColumn());

			if($join->getConditionSet() !== null)
				$string .= ' AND '.$this->buildConditionSetString($join->getConditionSet());

			$parts[] = $string.')';
		}

		return count($parts) === 0 ? null : implode(' ',$parts);
	}
}
