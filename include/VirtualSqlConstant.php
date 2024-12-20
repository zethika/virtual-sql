<?php

namespace VirtualSql;

class VirtualSqlConstant
{
    /**
     * OPERATORS
     */
    const OPERATOR_AND = 'AND';
    const OPERATOR_OR = 'OR';

    /**
     * JOINS
     */
    const JOIN_TYPE_INNER = 'INNER';
    const JOIN_TYPE_LEFT = 'LEFT';
    const JOIN_TYPE_OUTER = 'OUTER';
    const JOIN_TYPE_RIGHT = 'RIGHT';

    /**
     * COMPARATORS
     */
    const COMPARATOR_EQUALS = '=';
    const COMPARATOR_NOT_EQUALS = '!=';
    const COMPARATOR_LIKE = 'LIKE';
    const COMPARATOR_NOT_LIKE = 'NOT LIKE';
    const COMPARATOR_LESS_THAN = '<';
    const COMPARATOR_LESS_EQUAL_THAN = '<=';
    const COMPARATOR_GREATER_EQUAL_THAN = '>=';
    const COMPARATOR_GREATER_THAN = '>';
    const COMPARATOR_IN = 'IN';
    const COMPARATOR_NOT_IN = 'NOT IN';
    const COMPARATOR_IS = 'IS';
    const COMPARATOR_IS_NOT = 'IS NOT';
    const COMPARATOR_BETWEEN = 'BETWEEN';
    const COMPARATOR_COUNT = 'COUNT';
    const COMPARATOR_NOT_COUNT = 'NOT COUNT';

    /**
     * KEYWORDS
     */
    const KEYWORD_WILDCARD = '*';

    /**
     * STRINGS
     */
    const COLUMN_TYPE_VARCHAR = 'varchar';
    const COLUMN_TYPE_CHAR = 'char';
    const COLUMN_TYPE_LONGTEXT = 'longtext';
    const COLUMN_TYPE_MEDIUMTEXT = 'mediumtext';
    const COLUMN_TYPE_TINYTEXT = 'tinytext';
    const COLUMN_TYPE_TEXT = 'text';
    const COLUMN_TYPE_JSON = 'json';
    const COLUMN_TYPE_BIT = 'bit';

    const COLUMN_TEXT_TYPES = [self::COLUMN_TYPE_VARCHAR, self::COLUMN_TYPE_CHAR];

    /**
     * BLOBS
     */
    const COLUMN_TYPE_TINYBLOB = 'tinyblob';
    const COLUMN_TYPE_MEDIUMBLOB = 'mediumblob';
    const COLUMN_TYPE_BLOB = 'blob';
    const COLUMN_TYPE_LONGBLOB = 'longblob';


    /**
     * NUMBER
     */
    const COLUMN_TYPE_TINYINT = 'tinyint';
    const COLUMN_TYPE_SMALLINT = 'smallint';
    const COLUMN_TYPE_MEDIUMINT = 'mediumint';
    const COLUMN_TYPE_INT = 'int';
    const COLUMN_TYPE_BIGINT = 'bigint';
    const COLUMN_TYPE_DECIMAL = 'decimal';
    const COLUMN_TYPE_DOUBLE = 'double';
    const COLUMN_TYPE_FLOAT = 'float';

    const COLUMN_NUMBER_TYPES = [self::COLUMN_TYPE_INT, self::COLUMN_TYPE_TINYINT, self::COLUMN_TYPE_BIGINT, self::COLUMN_TYPE_DECIMAL, self::COLUMN_TYPE_FLOAT];

    /**
     * DATES
     */
    const COLUMN_TYPE_DATE = 'date';
    const COLUMN_TYPE_DATETIME = 'datetime';
    const COLUMN_TYPE_TIMESTAMP = 'timestamp';
    const COLUMN_TYPE_TIME = 'time';
    const COLUMN_TYPE_YEAR = 'year';

    /**
     * GEOMETRY
     */
    const COLUMN_TYPE_GEOMETRY = 'geometry';
    const COLUMN_TYPE_POINT = 'point';
    const COLUMN_TYPE_LINESTRING = 'linestring';
    const COLUMN_TYPE_POLYGON = 'polygon';
    const COLUMN_TYPE_MULTIPOINT = 'multipoint';
    const COLUMN_TYPE_MULTILINESTRING = 'multilinestring';
    const COLUMN_TYPE_MULTIPOLYGON = 'multipolygon';
    const COLUMN_TYPE_GEOMETRYCOLLECTION = 'geometrycollection';

    /**
     * BINARY
     */
    const COLUMN_TYPE_BINARY = 'binary';
    const COLUMN_TYPE_VARBINARY = 'varbinary';

    const COLUMN_TYPE_ENUM = 'enum';
    const COLUMN_TYPE_SET = 'set';

    /**
     * DEFAULTS
     */
    const DEFAULT_CURRENT_TIMESTAMP = 'CURRENT_TIMESTAMP';

    /**
     * EXTRAS
     */
    const EXTRA_AUTO_INCREMENT = 'AUTO_INCREMENT';
    const EXTRA_UNIQUE = 'UNIQUE';
    const EXTRA_ON_UPDATE_CURRENT_TIMESTAMP = 'ON UPDATE ' . self::DEFAULT_CURRENT_TIMESTAMP;
    const EXTRA_PRIMARY_KEY = 'PRIMARY KEY';
    const EXTRA_KEY = 'KEY';
    const EXTRA_CONSTRAINT = 'CONSTRAINT';

    /**
     * FORMATS
     */
    const COLUMN_FORMAT_DATETIME = 'Y-m-d H:i:s';

    /**
     * ORDER
     */

    const ORDER_DIRECTION_ASC = 'ASC';
    const ORDER_DIRECTION_DESC = 'DESC';


}
