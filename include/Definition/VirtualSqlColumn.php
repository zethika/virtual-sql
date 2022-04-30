<?php

namespace VirtualSql\Definition;


use VirtualSql\VirtualSqlConstant;

class VirtualSqlColumn
{
    /**
     * @var string
     */
    private string $column;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var int|string|null
     */
    private $length;

    /**
     * @var bool
     */
    private bool $nullable;

    /**
     * @var string|callable<string>|null
     */
    private $defaultValue;

    /**
     * @var array
     */
    private array $extras;

    /**
     * @var VirtualSqlTable|null
     */
    private ?VirtualSqlTable $table;

    /**
     * @var string|null
     */
    private ?string $alias;

    /**
     * @param string $column
     * @param string $type
     * @param string|int|null $length
     * @param bool $nullable
     * @param callable|string|null $defaultValue
     * @param array $extras
     * @param VirtualSqlTable|null $table
     * @param string|null $alias
     */
    public function __construct(string $column, string $type, $length = null, bool $nullable = false, $defaultValue = null, array $extras = [], ?VirtualSqlTable $table = null, ?string $alias = null)
    {
        $this->column = $column;
        $this->type = $type;
        $this->length = $length;
        $this->nullable = $nullable;
        $this->defaultValue = $defaultValue;
        $this->extras = $extras;
        $this->table = $table;
        $this->alias = $alias;
    }


    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int|string|null
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return string|null
     */
    public function getDefaultValue(): ?string
    {
        return is_callable($this->defaultValue)
            ? call_user_func($this->defaultValue)
            : $this->defaultValue;
    }

    /**
     * @return array
     */
    public function getExtras(): array
    {
        return $this->extras;
    }

    /**
     * Returns whether the column is the primary key column
     */
    public function isPrimaryKeyColumn(): bool
    {
        return $this->hasExtra(VirtualSqlConstant::EXTRA_PRIMARY_KEY);
    }

    /**
     * @param string $extra
     * @return bool
     */
    public function hasExtra(string $extra): bool
    {
        return in_array($extra, $this->extras);
    }

    /**
     * @param string $extra
     * @return $this
     */
    public function addExtra(string $extra): VirtualSqlColumn
    {
        if (!$this->hasExtra($extra))
            $this->extras[] = $extra;

        return $this;
    }

    /**
     * @return VirtualSqlTable|null
     */
    public function getTable(): ?VirtualSqlTable
    {
        return $this->table;
    }

    /**
     * @param VirtualSqlTable|null $table
     * @return VirtualSqlColumn
     */
    public function setTable(?VirtualSqlTable $table): VirtualSqlColumn
    {
        $this->table = $table;
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
     * @return VirtualSqlColumn
     */
    public function setAlias(?string $alias): VirtualSqlColumn
    {
        $this->alias = $alias;
        return $this;
    }
}
