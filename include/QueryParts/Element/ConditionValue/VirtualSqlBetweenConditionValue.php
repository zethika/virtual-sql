<?php

namespace VirtualSql\QueryParts\Element\ConditionValue;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;

class VirtualSqlBetweenConditionValue extends VirtualSqlConditionValue
{
    /**
     * @var float|VirtualSqlColumn|int
     */
    private $start;

    /**
     * @var float|VirtualSqlColumn|int
     */
    private $end;

    /**
     * @param VirtualSqlColumn|int|float $start
     * @param VirtualSqlColumn|int|float $end
     * @throws InvalidQueryPartException
     */
    public function __construct($start, $end)
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
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return float|VirtualSqlColumn|int
     */
    public function getEnd()
    {
        return $this->end;
    }

    public function getValue(): array
    {
        return [$this->getStart(), $this->getEnd()];
    }
}
