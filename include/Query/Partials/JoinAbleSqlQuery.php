<?php

namespace VirtualSql\Query\Partials;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Definition\VirtualSqlTable;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\Query\VirtualSqlQuery;
use VirtualSql\QueryParts\Element\VirtualSqlConditionSet;
use VirtualSql\QueryParts\Element\VirtualSqlJoin;
use VirtualSql\VirtualSqlConstant;

abstract class JoinAbleSqlQuery extends VirtualSqlQuery
{

    /**
     * @var VirtualSqlJoin[]
     */
    protected array $joins = [];

    /**
     * @param VirtualSqlTable $baseTable
     * @param array $config
     */
    public function __construct(VirtualSqlTable $baseTable, array $config)
    {
        if (isset($config['joins']) && is_array($config['joins']))
        {
            foreach ($config['joins'] as $join)
            {
                if ($join instanceof VirtualSqlJoin)
                    $this->addJoinToQuery($join);
            }
        }

        parent::__construct($baseTable);
    }

    /**
     * @return VirtualSqlJoin[]
     */
    public function getJoins(): array
    {
        return $this->joins;
    }

    /**
     * @param VirtualSqlColumn $from
     * @param VirtualSqlColumn $to
     * @param VirtualSqlConditionSet|null $conditions
     * @return JoinAbleSqlQuery
     * @throws InvalidQueryPartException
     */
    public function leftJoin(VirtualSqlColumn $from, VirtualSqlColumn $to, ?VirtualSqlConditionSet $conditions = null): JoinAbleSqlQuery
    {
        return $this->join(VirtualSqlConstant::JOIN_TYPE_LEFT, ...func_get_args());
    }

    /**
     * @param VirtualSqlColumn $from
     * @param VirtualSqlColumn $to
     * @param VirtualSqlConditionSet|null $conditions
     * @return JoinAbleSqlQuery
     * @throws InvalidQueryPartException
     */
    public function rightJoin(VirtualSqlColumn $from, VirtualSqlColumn $to, ?VirtualSqlConditionSet $conditions = null): JoinAbleSqlQuery
    {
        return $this->join(VirtualSqlConstant::JOIN_TYPE_RIGHT, ...func_get_args());
    }

    /**
     * @param VirtualSqlColumn $from
     * @param VirtualSqlColumn $to
     * @param VirtualSqlConditionSet|null $conditions
     * @return JoinAbleSqlQuery
     * @throws InvalidQueryPartException
     */
    public function outerJoin(VirtualSqlColumn $from, VirtualSqlColumn $to, ?VirtualSqlConditionSet $conditions = null): JoinAbleSqlQuery
    {
        return $this->join(VirtualSqlConstant::JOIN_TYPE_OUTER, ...func_get_args());
    }

    /**
     * @param VirtualSqlColumn $from
     * @param VirtualSqlColumn $to
     * @param VirtualSqlConditionSet|null $conditions
     * @return JoinAbleSqlQuery
     * @throws InvalidQueryPartException
     */
    public function innerJoin(VirtualSqlColumn $from, VirtualSqlColumn $to, ?VirtualSqlConditionSet $conditions = null): JoinAbleSqlQuery
    {
        return $this->join(VirtualSqlConstant::JOIN_TYPE_INNER, ...func_get_args());
    }

    /**
     * @param string $joinType
     * @param VirtualSqlColumn $from
     * @param VirtualSqlColumn $to
     * @param VirtualSqlConditionSet|null $conditions
     * @return JoinAbleSqlQuery
     * @throws InvalidQueryPartException
     */
    public function join(string $joinType, VirtualSqlColumn $from, VirtualSqlColumn $to, ?VirtualSqlConditionSet $conditions = null): JoinAbleSqlQuery
    {
        return $this->addJoinToQuery(new VirtualSqlJoin(...func_get_args()));
    }


    /**
     * Adds a join to the query, while ensuring that the tables related to the join have been added to the local tables map
     *
     * @param VirtualSqlJoin $join
     * @param bool $allowDuplicates
     * @return JoinAbleSqlQuery
     */
    public function addJoinToQuery(VirtualSqlJoin $join, bool $allowDuplicates = true): JoinAbleSqlQuery
    {
        $fromTable = $join->getFromColumn()->getTable();
        if ($fromTable->getAlias() === null || !isset($this->tables[$fromTable->getAlias()]))
            $this->ensureTable($fromTable);

        $toTable = $join->getToColumn()->getTable();
        if ($toTable->getAlias() === null || !isset($this->tables[$toTable->getAlias()]))
            $this->ensureTable($toTable);

        $this->addJoin($join, $allowDuplicates);

        return $this;
    }

    /**
     * @param VirtualSqlJoin $join
     * @param bool $allowDuplicates
     * @return JoinAbleSqlQuery
     */
    protected function addJoin(VirtualSqlJoin $join, bool $allowDuplicates = true): JoinAbleSqlQuery
    {
        if ($allowDuplicates === false)
        {
            foreach ($this->getJoins() as $existingJoin)
            {
                if ($existingJoin->getToColumn()->getTable()->getName() !== $join->getToColumn()->getTable()->getName())
                    continue;

                if ($existingJoin->getFromColumn()->getTable()->getName() !== $join->getFromColumn()->getTable()->getName())
                    continue;

                return $this;
            }
        }

        $this->joins[] = $join;

        return $this;
    }

}
