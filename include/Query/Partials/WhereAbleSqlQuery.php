<?php

namespace VirtualSql\Query\Partials;

use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\QueryParts\Element\VirtualSqlCondition;
use VirtualSql\QueryParts\Element\VirtualSqlConditionSet;
use VirtualSql\VirtualSqlConstant;

abstract class WhereAbleSqlQuery extends JoinAbleSqlQuery
{
    /**
     * @var VirtualSqlConditionSet
     */
    protected VirtualSqlConditionSet $where;

    /**
     * @param VirtualSqlTable $baseTable
     * @param array $config
     */
    public function __construct(VirtualSqlTable $baseTable, array $config)
    {
        $this->where = isset($config['where']) && $config['where'] instanceof VirtualSqlConditionSet ? $config['where'] : new VirtualSqlConditionSet(VirtualSqlConstant::OPERATOR_AND);
        parent::__construct($baseTable, $config);
    }

    /**
     * @return VirtualSqlConditionSet
     */
    public function getWhere(): VirtualSqlConditionSet
    {
        return $this->where;
    }

    /**
     * @param VirtualSqlConditionSet $where
     * @return VirtualSqlQuery
     */
    public function setWhere(VirtualSqlConditionSet $where): VirtualSqlQuery
    {
        $this->where = $where;
        return $this;
    }

    /**
     * @param VirtualSqlCondition|VirtualSqlConditionSet $where
     * @return VirtualSqlQuery
     */
    public function addWhere($where): VirtualSqlQuery
    {
        $this->where->addCondition($where);
        return $this;
    }
}
