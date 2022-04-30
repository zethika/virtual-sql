<?php

namespace VirtualSql\Query\Partials;

use VirtualSql\Definition\VirtualSqlTable;

abstract class OffsetAbleSqlQuery extends LimitAbleSqlQuery
{
    /**
     * @var int|null
     */
    protected ?int $offset;

    /**
     * @param VirtualSqlTable $baseTable
     * @param array $config
     */
    public function __construct(VirtualSqlTable $baseTable, array $config)
    {
        $this->offset = isset($config['offset']) && is_numeric($config['offset']) ? (int)$config['offset'] : null;
        parent::__construct($baseTable, $config);
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     * @return OffsetAbleSqlQuery
     */
    public function setOffset(?int $offset): OffsetAbleSqlQuery
    {
        $this->offset = $offset;
        return $this;
    }
}
