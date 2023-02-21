<?php

namespace VirtualSql\Definition;

class VirtualSqlIndex
{
    private string $name;

    /**
     * @var string[]
     */
    private array $columns;

    /**
     * @var bool
     */
    private bool $isPrimary;

    /**
     * @param string $name
     * @param string[] $columns
     * @param bool $isPrimary
     */
    public function __construct(string $name, array $columns, bool $isPrimary)
    {
        $this->name = $name;
        $this->columns = $columns;
        $this->isPrimary = $isPrimary;
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
     * @return VirtualSqlIndex
     */
    public function setName(string $name): VirtualSqlIndex
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     * @return VirtualSqlIndex
     */
    public function setColumns(array $columns): VirtualSqlIndex
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPrimary(): bool
    {
        return $this->isPrimary;
    }

    /**
     * @param bool $isPrimary
     * @return VirtualSqlIndex
     */
    public function setIsPrimary(bool $isPrimary): VirtualSqlIndex
    {
        $this->isPrimary = $isPrimary;
        return $this;
    }
}
