<?php

namespace VirtualSql\QueryParts\Element\ConditionValue;

class VirtualSqlStringConditionValue extends VirtualSqlConditionValue
{

    /**
     * @var string
     */
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
