<?php

namespace VirtualSql\QueryParts\Element\ConditionValue;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\VirtualSqlSelectQuery;
use VirtualSql\VirtualSqlConstant;

abstract class VirtualSqlConditionValue
{
    /**
     * @param VirtualSqlColumn $column
     * @param mixed $value
     * @param null $value2
     * @return VirtualSqlArrayConditionValue|VirtualSqlBetweenConditionValue|VirtualSqlNumberConditionValue|VirtualSqlStringConditionValue
     * @throws InvalidQueryPartException
     */
    public static function factory(VirtualSqlColumn $column, $value, $value2 = null): VirtualSqlConditionValue
    {
        try
        {
            if ($value2 !== null)
                return new VirtualSqlBetweenConditionValue($value, $value2);

            if (is_array($value))
                return new VirtualSqlArrayConditionValue($value);

            if (in_array($column->getType(), VirtualSqlConstant::COLUMN_NUMBER_TYPES))
                return new VirtualSqlNumberConditionValue($value, in_array($column->getType(), [VirtualSqlConstant::COLUMN_TYPE_DECIMAL, VirtualSqlConstant::COLUMN_TYPE_FLOAT]));

            if($value instanceof VirtualSqlSelectQuery)
                return new VirtualSqlCompositeQueryConditionValue($value);

            return new VirtualSqlStringConditionValue($value);
        } catch (InvalidQueryPartException $e)
        {
            throw new InvalidQueryPartException($e->getMessage() . ' on column "' . $column->getColumn() . '"');
        }

    }

    /**
     * @return mixed
     */
    abstract public function getValue();
}
