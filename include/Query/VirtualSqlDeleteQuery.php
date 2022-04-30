<?php

namespace VirtualSql\Query;

use VirtualSql\Query\Traits\JoinAbleQueryTrait;
use VirtualSql\Query\Traits\LimitAbleQueryTrait;
use VirtualSql\Query\Traits\WhereAbleQueryTrait;

class VirtualSqlDeleteQuery extends VirtualSqlQuery
{
	use WhereAbleQueryTrait;
	use JoinAbleQueryTrait;
	use LimitAbleQueryTrait;

	// Where
	// limit
}
