<?php

namespace Parser;

use VirtualSql\Parser\VirtualSqlCreateTableStatementParser;
use VirtualSql\VirtualSqlConstant;

require_once __DIR__.'/../AbstractVirtualSqlTestCase.php';
class StatementParserTest extends \AbstractVirtualSqlTestCase
{
    public function testPlainCreateStatement()
    {
        $table = VirtualSqlCreateTableStatementParser::getInstance()->parseStatement(self::PLAIN_TABLE_WITH_TWO_COLUMNS);

        $this->assertEquals("plain_table",$table->getName());
        $this->assertCount(2, $table->getColumns());

        $primaryKeys = $table->getPrimaryKeyColumns();
        $this->assertCount(1,$primaryKeys);
        $this->assertEquals(end($primaryKeys),$table->getColumn('id'));
    }

    public function testCompositePrimaryKey()
    {
        $table = VirtualSqlCreateTableStatementParser::getInstance()->parseStatement(self::COMPOSITE_TABLE_WITH_2_COMPOSITE_PRIMARY_KEYS);

        $this->assertEquals("composite_table",$table->getName());
        $this->assertCount(3, $table->getColumns());

        $primaryKeys = $table->getPrimaryKeyColumns();
        $this->assertCount(2,$primaryKeys);
    }

    public function testColumnTypes()
    {
        $table = VirtualSqlCreateTableStatementParser::getInstance()->parseStatement(self::TABLE_WITH_ALL_TYPES);

        $this->assertEquals("types_table",$table->getName());
        $this->assertCount(36, $table->getColumns());

        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_TINYINT,$table->getColumn('tinyint_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_SMALLINT,$table->getColumn('smallint_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_MEDIUMINT,$table->getColumn('mediumint_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_INT,$table->getColumn('int_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_BIGINT,$table->getColumn('bigint_type')->getType());

        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_FLOAT,$table->getColumn('float_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_DOUBLE,$table->getColumn('double_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_DECIMAL,$table->getColumn('decimal_type')->getType());

        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_BIT,$table->getColumn('bit_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_CHAR,$table->getColumn('char_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_TINYTEXT,$table->getColumn('tinytext_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_TEXT,$table->getColumn('text_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_MEDIUMTEXT,$table->getColumn('mediumtext_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_LONGTEXT,$table->getColumn('longtext_type')->getType());

        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_TINYBLOB,$table->getColumn('tinyblob_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_BLOB,$table->getColumn('blob_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_MEDIUMBLOB,$table->getColumn('mediumblob_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_LONGBLOB,$table->getColumn('longblob_type')->getType());

        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_BINARY,$table->getColumn('binary_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_VARBINARY,$table->getColumn('varbinary_type')->getType());

        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_ENUM,$table->getColumn('enum_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_SET,$table->getColumn('set_type')->getType());

        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_DATE,$table->getColumn('date_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_DATETIME,$table->getColumn('datetime_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_TIMESTAMP,$table->getColumn('timestamp_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_TIME,$table->getColumn('time_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_YEAR,$table->getColumn('year_type')->getType());

        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_GEOMETRY,$table->getColumn('geometry_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_POINT,$table->getColumn('point_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_LINESTRING,$table->getColumn('linestring_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_POLYGON,$table->getColumn('polygon_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_MULTIPOINT,$table->getColumn('multipoint_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_MULTILINESTRING,$table->getColumn('multilinestring_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_MULTIPOLYGON,$table->getColumn('multipolygon_type')->getType());
        $this->assertEquals(VirtualSqlConstant::COLUMN_TYPE_GEOMETRYCOLLECTION,$table->getColumn('geometrycollection_type')->getType());
    }
}