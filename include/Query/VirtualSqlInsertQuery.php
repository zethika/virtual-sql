<?php

namespace VirtualSql\Query;

use VirtualSql\Definition\VirtualSqlTable;

class VirtualSqlInsertQuery extends VirtualSqlQuery
{

	// Table
	// Values headers
	// Values rows
	// On insert update
	public function __construct(VirtualSqlTable $table, array $config)
	{
		parent::__construct($table,$config);
	}
}
