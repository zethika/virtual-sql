<?php

namespace VirtualSql\Query;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Exceptions\UndefinedQueryPartException;
use VirtualSql\SqlBuilder\VirtualSqlBuilder;

/**
 * VirtualSqlQuery and its child classes are responsible for interacting with the consuming PHP app.
 * They provide a series of helper functions for adding and manipulating their represented query, allowing programmatic changing of the query from the consumer.
 * They finally provide functions to convert them into their corresponding SQL query / named parameter arrays.
 */
abstract class VirtualSqlQuery
{
    const TYPE_SELECT = 0;
    const TYPE_INSERT = 1;
    const TYPE_UPDATE = 2;
    const TYPE_DELETE = 3;
    const TYPE_UNION = 4;

    const TYPE_CLASS_MAP = [
        self::TYPE_SELECT => VirtualSqlSelectQuery::class,
        self::TYPE_INSERT => VirtualSqlInsertQuery::class,
        self::TYPE_UPDATE => VirtualSqlUpdateQuery::class,
        self::TYPE_DELETE => VirtualSqlDeleteQuery::class,
        self::TYPE_UNION => VirtualSqlUnionQuery::class
    ];

    /**
     * @param int $type
     * @param VirtualSqlTable|null $baseTable
     * @param array $config
     * @return VirtualSqlQuery
     */
    public static function factory(int $type, ?VirtualSqlTable $baseTable = null, array $config = []): VirtualSqlQuery
    {
        $classname = self::TYPE_CLASS_MAP[$type];
        return new $classname($baseTable, $config);
    }

    /**
     * @var VirtualSqlTable|null
     */
    protected ?VirtualSqlTable $baseTable = null;

    /**
     * A map of the tables known to the query builder.
     * The key is the table alias for easier referencing
     *
     * @var VirtualSqlTable[]
     */
    protected array $tables = [];

    /**
     * @var VirtualSqlBuilder
     */
    protected VirtualSqlBuilder $builder;

    /**
     * @param VirtualSqlTable|null $baseTable
     */
    public function __construct(?VirtualSqlTable $baseTable = null)
    {
        $this->baseTable = $baseTable;

        if($baseTable !== null)
            $this->ensureTable($baseTable);
    }

    /**
     * @return VirtualSqlBuilder
     */
    public function getBuilder(): VirtualSqlBuilder
    {
        return $this->builder;
    }

    /**
     * @return VirtualSqlTable
     */
    public function getBaseTable(): VirtualSqlTable
    {
        return $this->baseTable;
    }

    /**
     * @return VirtualSqlTable[]
     */
    public function getTables(): array
    {
        return $this->tables;
    }

    /**
     * @param VirtualSqlTable $table
     * @param string $column
     * @return VirtualSqlColumn
     * @throws UndefinedQueryPartException
     */
    public function getTableColumn(VirtualSqlTable $table, string $column): VirtualSqlColumn
    {
        $columnInstance = null;
        if ($table->getAlias() !== null)
        {
            if (isset($this->tables[$table->getAlias()]) !== false)
                $columnInstance = $this->tables[$table->getAlias()]->getColumn($column);
        } else
        {
            foreach ($this->getTables() as $definedTable)
            {
                if ($table->getName() === $definedTable->getName())
                    $columnInstance = $definedTable->getColumn($column);
            }
        }

        if ($columnInstance === null)
            throw new UndefinedQueryPartException('Column "' . $column . '" does not exist on table "' . $table->getName() . '" or the table has not been added to the builder.');

        return $columnInstance;
    }


    /**
     * Adds a given table to the known tables map for the query builder
     * This is private, since it should never be used directly from outside but rather only indirectly via adding joins and via the initial tables
     */
    protected function ensureTable(VirtualSqlTable $table): void
    {
        $this->ensureAlias($table);

        if (isset($this->tables[$table->getAlias()]))
            return;

        $this->tables[$table->getAlias()] = $table;
    }

    /**
     * Ensures that the given table has a unique alias in the local map
     */
    protected function ensureAlias(VirtualSqlTable $table): void
    {
        if ($table->getAlias() !== null)
            return;

        $i = count($this->tables);
        do
        {
            $table->setAlias(self::generateTableAlias($i));
            $i++;
        } while (isset($this->tables[$table->getAlias()]));
    }

    /**
     * @param int $number
     * @return string
     */
    public static function generateTableAlias(int $number): string
    {
        return '_t' . $number;
    }

    /**
     * @return string
     * @throws InvalidQueryPartException
     */
    public function getSql(): string
    {
        return $this->builder->getSql();
    }

    /**
     * @return array
     */
    public function getNamedParameters(): array
    {
        return $this->builder->getNamedParameters();
    }

}
