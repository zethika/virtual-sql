<?php

namespace VirtualSql\QueryParts\Element;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\QueryParts\Element\ConditionValue\VirtualSqlConditionValue;
use VirtualSql\VirtualSqlConstant;

class VirtualSqlCondition
{
    public static array $acceptedComparators = [
        VirtualSqlConstant::COMPARATOR_EQUALS => VirtualSqlConstant::COMPARATOR_EQUALS,
        VirtualSqlConstant::COMPARATOR_NOT_EQUALS => VirtualSqlConstant::COMPARATOR_NOT_EQUALS,
        VirtualSqlConstant::COMPARATOR_LIKE => VirtualSqlConstant::COMPARATOR_LIKE,
        VirtualSqlConstant::COMPARATOR_NOT_LIKE => VirtualSqlConstant::COMPARATOR_NOT_LIKE,
        VirtualSqlConstant::COMPARATOR_LESS_THAN => VirtualSqlConstant::COMPARATOR_LESS_THAN,
        VirtualSqlConstant::COMPARATOR_LESS_EQUAL_THAN => VirtualSqlConstant::COMPARATOR_LESS_EQUAL_THAN,
        VirtualSqlConstant::COMPARATOR_GREATER_EQUAL_THAN => VirtualSqlConstant::COMPARATOR_GREATER_EQUAL_THAN,
        VirtualSqlConstant::COMPARATOR_GREATER_THAN => VirtualSqlConstant::COMPARATOR_GREATER_THAN,
        VirtualSqlConstant::COMPARATOR_IN => VirtualSqlConstant::COMPARATOR_IN,
        VirtualSqlConstant::COMPARATOR_NOT_IN => VirtualSqlConstant::COMPARATOR_NOT_IN,
        VirtualSqlConstant::COMPARATOR_BETWEEN => VirtualSqlConstant::COMPARATOR_BETWEEN,
    ];

    /**
     * @var VirtualSqlColumn
     */
    private VirtualSqlColumn $column;

    /**
     * @var VirtualSqlConditionValue
     */
    private VirtualSqlConditionValue $value;

    /**
     * @var string
     */
    private string $comparator;

    /**
     * @param VirtualSqlColumn $column
     * @param VirtualSqlConditionValue $value
     * @param string $comparator
     * @throws InvalidQueryPartException
     */
    public function __construct(VirtualSqlColumn $column, VirtualSqlConditionValue $value, string $comparator)
    {
        $this->setComparator($comparator);
        $this->column = $column;
        $this->value = $value;
    }

    /**
     * @return VirtualSqlColumn
     */
    public function getColumn(): VirtualSqlColumn
    {
        return $this->column;
    }

    /**
     * @param VirtualSqlColumn $column
     * @return VirtualSqlCondition
     */
    public function setColumn(VirtualSqlColumn $column): VirtualSqlCondition
    {
        $this->column = $column;
        return $this;
    }

    /**
     * @return VirtualSqlConditionValue
     */
    public function getValue(): VirtualSqlConditionValue
    {
        return $this->value;
    }

    /**
     * @param VirtualSqlConditionValue $value
     * @return VirtualSqlCondition
     */
    public function setValue(VirtualSqlConditionValue $value): VirtualSqlCondition
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getComparator(): string
    {
        return $this->comparator;
    }

    /**
     * @param string $comparator
     * @return VirtualSqlCondition
     * @throws InvalidQueryPartException
     */
    public function setComparator(string $comparator): VirtualSqlCondition
    {
        if (isset(self::$acceptedComparators[$comparator]) === false)
            throw new InvalidQueryPartException('"' . $comparator . '" is not a valid condition comparator');

        $this->comparator = $comparator;
        return $this;
    }
}
