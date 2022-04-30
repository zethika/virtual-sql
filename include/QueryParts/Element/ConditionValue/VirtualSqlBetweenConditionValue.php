<?php

namespace VirtualSql\QueryParts\Element\ConditionValue;

use JetBrains\PhpStorm\Pure;
use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;

class VirtualSqlBetweenConditionValue extends VirtualSqlConditionValue
{
    /**
     * @var float|VirtualSqlColumn|int
     */
    private float|VirtualSqlColumn|int $start;

    /**
     * @var float|VirtualSqlColumn|int
     */
    private float|VirtualSqlColumn|int $end;

    /**
     * @param VirtualSqlColumn|int|float $start
     * @param VirtualSqlColumn|int|float $end
     * @throws InvalidQueryPartException
     */
    public function __construct(VirtualSqlColumn|int|float $start, VirtualSqlColumn|int|float $end)
    {
        if ((!is_numeric($start) || is_string($start)) && !$start instanceof VirtualSqlColumn)
            throw new InvalidQueryPartException('Start is neither a number, nor an instance of a VirtualSqlColumn');

        if ((!is_numeric($end) || is_string($end)) && !$end instanceof VirtualSqlColumn)
            throw new InvalidQueryPartException('End is neither a number, nor an instance of a VirtualSqlColumn');

        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return float|VirtualSqlColumn|int
     */
    public function getStart(): float|int|VirtualSqlColumn
    {
        return $this->start;
    }

    /**
     * @return float|VirtualSqlColumn|int
     */
    public function getEnd(): float|int|VirtualSqlColumn
    {
        return $this->end;
    }

    #[Pure] public function getValue(): array
    {
        return [$this->getStart(), $this->getEnd()];
    }
}
