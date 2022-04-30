<?php

namespace VirtualSql\QueryParts\Element\ConditionValue;

use VirtualSql\Exceptions\InvalidQueryPartException;

class VirtualSqlNumberConditionValue extends VirtualSqlConditionValue
{
    /**
     * @var float|int
     */
    private $number;

    /**
     * @param int|float $number
     * @param bool $parseToFloat
     * @throws InvalidQueryPartException
     */
    public function __construct($number, bool $parseToFloat = false)
    {
        if (is_string($number) && is_numeric($number))
            $number = $parseToFloat ? (float)$number : (int)$number;

        if (!is_numeric($number))
            throw new InvalidQueryPartException('Value "' . $number . '" is not a number');

        $this->number = $number;
    }

    /**
     * @return float|int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return float|int
     */
    public function getValue()
    {
        return $this->getNumber();
    }
}
