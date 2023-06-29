<?php

namespace Query\Select;

use VirtualSql\Parser\VirtualSqlCreateTableStatementParser;
use VirtualSql\Query\VirtualSqlQuery;

require_once __DIR__.'/../../AbstractVirtualSqlTestCase.php';
class PlainSelectTest extends \AbstractVirtualSqlTestCase
{
    public function testDefaultSelect()
    {
        $table = VirtualSqlCreateTableStatementParser::getInstance()->parseStatement(self::PLAIN_TABLE_WITH_TWO_COLUMNS);

        $query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_SELECT,$table);

        $this->assertEquals('SELECT * FROM `plain_table` as _t0',$query->getSql());
    }

    public function testSpecificColumnSelect()
    {
        $table = VirtualSqlCreateTableStatementParser::getInstance()->parseStatement(self::PLAIN_TABLE_WITH_TWO_COLUMNS);

        $query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_SELECT,$table);
        $query->setSelects([$table->getColumn('other_column')]);
        $this->assertEquals('SELECT _t0.`other_column` FROM `plain_table` as _t0',$query->getSql());

        $query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_SELECT,$table);
        $column = $table->getColumn('other_column');
        $column->setAlias('alias_test');
        $query->setSelects([$column]);
        $this->assertEquals('SELECT _t0.`other_column` as `alias_test` FROM `plain_table` as _t0',$query->getSql());
    }

    public function testCommandColumnSelect()
    {
        $table = VirtualSqlCreateTableStatementParser::getInstance()->parseStatement(self::PLAIN_TABLE_WITH_TWO_COLUMNS);

        $query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_SELECT,$table);
        $column = $table->getCommandColumn('COUNT(*)');
        $query->setSelects([$column]);
        $this->assertEquals('SELECT COUNT(*) FROM `plain_table` as _t0',$query->getSql());

        $query = VirtualSqlQuery::factory(VirtualSqlQuery::TYPE_SELECT,$table);
        $column = $table->getCommandColumn('COUNT(*)');
        $column->setAlias('total_counter');
        $query->setSelects([$column]);
        $this->assertEquals('SELECT COUNT(*) as `total_counter` FROM `plain_table` as _t0',$query->getSql());
    }
}