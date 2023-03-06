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
    const COMPARATOR_BETWEEN = 'BETWEEN';

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
    const COLUMN_TYPE_TEXT = 'text';
    const COLUMN_TYPE_BLOB = 'blob';
    const COLUMN_TYPE_JSON = 'json';

    const COLUMN_TEXT_TYPES = [self::COLUMN_TYPE_VARCHAR, self::COLUMN_TYPE_CHAR];

    /**
     * NUMBER
     */
    const COLUMN_TYPE_INT = 'int';
    const COLUMN_TYPE_TINYINT = 'tinyint';
    const COLUMN_TYPE_BIGINT = 'bigint';
    const COLUMN_TYPE_DECIMAL = 'decimal';
    const COLUMN_TYPE_FLOAT = 'float';

    const COLUMN_NUMBER_TYPES = [self::COLUMN_TYPE_INT, self::COLUMN_TYPE_TINYINT, self::COLUMN_TYPE_BIGINT, self::COLUMN_TYPE_DECIMAL, self::COLUMN_TYPE_FLOAT];

    /**
     * DATES
     */
    const COLUMN_TYPE_DATETIME = 'datetime';
    const COLUMN_TYPE_TIMESTAMP = 'timestamp';

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

    /**
     * FORMATS
     */
    const COLUMN_FORMAT_DATETIME = 'Y-m-d H:i:s';


}
