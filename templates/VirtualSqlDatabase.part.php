<?php

use VirtualSql\Definition\VirtualSqlDatabase;

class VSDatabase_TMPLDATABASENAME extends VirtualSqlDatabase
{
	public function __construct()
	{
		parent::__construct([
			TMPLTABLEDECLARATIONSARRAY
		]);
	}
}
