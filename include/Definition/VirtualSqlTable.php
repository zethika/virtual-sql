<?php

namespace VirtualSql\Definition;

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
     * @var array
     */
    private ?array $columnNameIndexes = null;


    /**
     * @param string $name
     * @param VirtualSqlColumn[] $columns
     * @param string|null $alias
     */
    public function __construct(string $name, array $columns, ?string $alias = null)
    {
        $this->name = $name;
        $this->columns = $columns;

        foreach ($columns as $column)
        {
            if ($column->getTable() === null)
                $column->setTable($this);
        }
        $this->alias = $alias;
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
     *
     */
    private function populateColumnNameIndexes()
    {
        if ($this->columnNameIndexes !== null)
            return;

        $this->columnNameIndexes = [];
        foreach ($this->getColumns() as $index => $columnInstance)
        {
            $this->columnNameIndexes[$columnInstance->getColumn()] = $index;
        }
    }

    /**
     * @param string $column
     * @return VirtualSqlColumn|null
     */
    public function getColumn(string $column): ?VirtualSqlColumn
    {
        $this->populateColumnNameIndexes();
        return isset($this->columnNameIndexes[$column]) ? $this->getColumns()[$this->columnNameIndexes[$column]] : null;
    }
}
