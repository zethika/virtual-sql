<?php

namespace VirtualSql\SqlBuilder\Partials;

abstract class OffsetAbleSqlBuilder extends LimitAbleSqlBuilder
{
	/**
	 * @param int|null $offset
	 * @return string|null
	 */
	protected function buildOffsetString(?int $offset): ?string
	{
		return $offset === null ? null : 'OFFSET '.$offset;
	}
}
