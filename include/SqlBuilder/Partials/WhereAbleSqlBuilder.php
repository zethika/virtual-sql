<?php

namespace VirtualSql\SqlBuilder\Partials;

use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\QueryParts\Element\VirtualSqlConditionSet;

abstract class WhereAbleSqlBuilder extends JoinAbleSqlBuilder
{
	/**
	 * @param VirtualSqlConditionSet $conditions
	 * @return string|null
	 * @throws InvalidQueryPartException
	 */
	protected function buildWhereString(VirtualSqlConditionSet $conditions): ?string
	{
		if(count($conditions->getConditions()) === 0)
			return null;

		return 'WHERE '.$this->buildConditionSetString($conditions);
	}
}
