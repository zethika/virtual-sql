<?php

namespace VirtualSql\QueryParts\Element\ConditionValue;

class VirtualSqlArrayConditionValue extends VirtualSqlConditionValue
{

    /**
     * @var array
     */
    private array $array;

    /**
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->array;
    }

    public function getValue()
    {
        return $this->getArray();
    }
}
