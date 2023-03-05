<?php

namespace VirtualSql\Definition;

use VirtualSql\VirtualSqlConstant;

class VirtualSqlTable
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var VirtualSqlColumn[]
     */
    private array $columns;

    /**
     * @var string|null
     */
    private ?string $alias;

    /**
     * @var array|null
     */
    private ?array $columnNameIndexes = null;

    /**
     * @var VirtualSqlIndex[]
     */
    private array $indexes;


    /**
     * @param string $name
     * @param VirtualSqlColumn[] $columns
     * @param string|null $alias
     * @param VirtualSqlIndex[] $indexes
     */
    public function __construct(string $name, array $columns, ?string $alias = null, array $indexes = [])
    {
        $this->name = $name;
        $this->columns = $columns;

        foreach ($columns as $column)
        {
            if ($column->getTable() === null)
                $column->setTable($this);
        }
        $this->alias = $alias;
        $this->indexes = $indexes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return VirtualSqlTable
     */
    public function setName(string $name): VirtualSqlTable
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return VirtualSqlColumn[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param VirtualSqlColumn[] $columns
     * @return VirtualSqlTable
     */
    public function setColumns(array $columns): VirtualSqlTable
    {
        $this->columns = $columns;
        $this->columnNameIndexes = null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param string|null $alias
     * @return VirtualSqlTable
     */
    public function setAlias(?string $alias): VirtualSqlTable
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return VirtualSqlIndex[]
     */
    public function getIndexes(): array
    {
        return $this->indexes;
    }

    /**
     * @param VirtualSqlIndex[] $indexes
     * @return VirtualSqlTable
     */
    public function setIndexes(array $indexes): VirtualSqlTable
    {
        $this->indexes = $indexes;
        return $this;
    }

    /**
     *
     */
    private function populateColumnNameIndexes()
    {
        if ($this->columnNameIndexes !== null)
            return;

        $this->columnNameIndexes = [];
        foreach ($this->getColumns() as $index => $columnInstance)
        {
            $this->columnNameIndexes[mb_strtolower($columnInstance->getColumn())] = $index;
        }
    }

    /**
     * @param string $column
     * @return VirtualSqlColumn|null
     */
    public function getColumn(string $column): ?VirtualSqlColumn
    {
        $this->populateColumnNameIndexes();
        $lowered = mb_strtolower($column);
        return isset($this->columnNameIndexes[$lowered]) ? $this->getColumns()[$this->columnNameIndexes[$lowered]] : null;
    }

    /**
     * @param string $command
     * @return VirtualSqlColumn
     */
    public function getCommandColumn(string $command): VirtualSqlColumn
    {
        return new VirtualSqlColumn($command,VirtualSqlConstant::COLUMN_TYPE_VARCHAR,null,false,null,[],$this,null,true);
    }

    /**
     * @return VirtualSqlColumn[]
     */
    public function getPrimaryKeyColumns(): array
    {
        return array_filter($this->getColumns(),fn(VirtualSqlColumn $column) => $column->isPrimaryKeyColumn());
    }
}
