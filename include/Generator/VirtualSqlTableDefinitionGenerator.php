<?php

namespace VirtualSql\Generator;

use PDO;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Exceptions\InvalidStatementPartException;
use VirtualSql\Parser\VirtualSqlCreateTableStatementParser;
use VirtualSql\Traits\SingletonTrait;
use VirtualSql\VirtualSqlQueryHelper;

/**
 * Helper class to generate a class hierarchy describing the database provided in a given PDO connection.
 */
class VirtualSqlTableDefinitionGenerator
{
    use SingletonTrait;

    /**
     * @var VirtualSqlQueryHelper
     */
    private VirtualSqlQueryHelper $db;

    /**
     * @var string|bool[]
     */
    private array $tables = [];

    /**
     * @param PDO $pdo
     */
    public function init(PDO $pdo)
    {
        $this->db = new VirtualSqlQueryHelper($pdo);
        $this->tables = [];
        $this->determineTables();
    }


    /**
     * @param string $table
     * @param bool $forceFetch
     * @return VirtualSqlTable
     * @throws InvalidStatementPartException
     */
    public function generateTableDefinition(string $table, bool $forceFetch = false): VirtualSqlTable
    {
        if (!isset($this->tables[$table]) && $forceFetch !== true)
            throw new InvalidStatementPartException('Unknown table "' . $table . '"');

        if (!isset($this->tables[$table]) || $this->tables[$table] === false || $forceFetch)
            $this->tables[$table] = $this->db->fetch('SHOW CREATE TABLE `' . $table.'`')['Create Table'];

        if (!isset($this->tables[$table]))
            throw new InvalidStatementPartException('Unknown table "' . $table . '"');

        return VirtualSqlCreateTableStatementParser::getInstance()->parseStatement($this->tables[$table]);
    }


    /**
     * Determines tables present in the database
     */
    private function determineTables()
    {
        $tables = $this->db->fetchAll('SHOW TABLES');
        foreach ($tables as $data)
            $this->tables[end($data)] = false;
    }
}
