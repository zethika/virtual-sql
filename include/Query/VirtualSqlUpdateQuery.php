<?php

namespace VirtualSql\Query;

use VirtualSql\Query\Traits\JoinAbleQueryTrait;
use VirtualSql\Query\Traits\WhereAbleQueryTrait;

class VirtualSqlUpdateQuery extends VirtualSqlQuery
{
	use WhereAbleQueryTrait;
	use JoinAbleQueryTrait;

	// Values headers
	// Values rows
	// Where
}
