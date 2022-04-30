<?php

namespace VirtualSql\SqlBuilder\Partials;

abstract class LimitAbleSqlBuilder extends WhereAbleSqlBuilder
{
	/**
	 * @param int|null $limit
	 * @return string|null
	 */
	protected function buildLimitString(?int $limit): ?string
	{
		return $limit === null ? null : 'LIMIT '.$limit;
	}
}
