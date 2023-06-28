<?php

namespace PHPSTORM_META
{
    override(\VirtualSql\Query\VirtualSqlQuery::factory(), map([
        \VirtualSql\Query\VirtualSqlQuery::TYPE_SELECT => \VirtualSql\Query\VirtualSqlSelectQuery::class,
        \VirtualSql\Query\VirtualSqlQuery::TYPE_INSERT => \VirtualSql\Query\VirtualSqlInsertQuery::class,
        \VirtualSql\Query\VirtualSqlQuery::TYPE_UPDATE => \VirtualSql\Query\VirtualSqlUpdateQuery::class,
        \VirtualSql\Query\VirtualSqlQuery::TYPE_DELETE => \VirtualSql\Query\VirtualSqlDeleteQuery::class,
        \VirtualSql\Query\VirtualSqlQuery::TYPE_UNION => \VirtualSql\Query\VirtualSqlUnionQuery::class,
    ]));
}
