<?php

namespace Query\Select;

use VirtualSql\Parser\VirtualSqlCreateTableStatementParser;
use VirtualSql\Query\VirtualSqlQuery;

require_once __DIR__.'/../../AbstractVirtualSqlTestCase.php';
class PlainSelectTest extends \AbstractVirtualSqlTestCase
{
    public function testDefaultSelect()
    {
        $table = VirtualSqlCreateTableStatementParser::getInstance()->parseStatement("CREATE TABLE `plain_table` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `other_column` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_SELECT,$table);

        $this->assertEquals('SELECT * FROM `plain_table` as _t0',$query->getSql());
    }

    public function testSpecificSelect()
    {
        $table = VirtualSqlCreateTableStatementParser::getInstance()->parseStatement("CREATE TABLE `plain_table` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `other_column` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_SELECT,$table);
        $query->setSelects([$table->getColumn('other_column')]);
        $this->assertEquals('SELECT _t0.`other_column` FROM `plain_table` as _t0',$query->getSql());
    }
}