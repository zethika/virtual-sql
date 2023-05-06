<?php

namespace VirtualSql\QueryParts\Element;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\VirtualSqlConstant;

class VirtualSqlOrderPart
{
    public static array $acceptedOrderDirections = [
        VirtualSqlConstant::ORDER_DIRECTION_ASC => VirtualSqlConstant::ORDER_DIRECTION_ASC,
        VirtualSqlConstant::ORDER_DIRECTION_DESC => VirtualSqlConstant::ORDER_DIRECTION_DESC,
    ];

    private VirtualSqlColumn $column;
    private string $order;

    /**
     * @param VirtualSqlColumn $column
     * @param string $order
     * @throws InvalidQueryPartException
     */
    public function __construct(VirtualSqlColumn $column, string $order)
    {
        if(!in_array($order,self::$acceptedOrderDirections))
            throw new InvalidQueryPartException('Order direction "'.$order.'" is not valid');

        $this->column = $column;
        $this->order = $order;
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
     * @return VirtualSqlOrderPart
     */
    public function setColumn(VirtualSqlColumn $column): VirtualSqlOrderPart
    {
        $this->column = $column;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @param string $order
     * @return VirtualSqlOrderPart
     */
    public function setOrder(string $order): VirtualSqlOrderPart
    {
        $this->order = $order;
        return $this;
    }

}