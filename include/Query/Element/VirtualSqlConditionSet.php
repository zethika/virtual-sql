<?php

namespace VirtualSql\Query\Element;

use VirtualSql\VirtualSql;

class VirtualSqlConditionSet
{
	public static array $acceptedOperators = [
		VirtualSql::OPERATOR_AND => VirtualSql::OPERATOR_AND,
		VirtualSql::OPERATOR_OR => VirtualSql::OPERATOR_OR
	];

	/**
	 * @var string
	 */
	private string $operator;

	/**
	 * @var VirtualSqlCondition[]|VirtualSqlConditionSet[]
	 */
	private array $conditions;

	/**
	 * @param string $operator
	 * @param VirtualSqlCondition[]|VirtualSqlConditionSet[] $conditions
	 */
	public function __construct(string $operator, array $conditions = [])
	{
		$this->operator = $operator;
		$this->conditions = $conditions;
	}

	/**
	 * @return string
	 */
	public function getOperator(): string
	{
		return $this->operator;
	}

	/**
	 * @param string $operator
	 * @return VirtualSqlConditionSet
	 */
	public function setOperator(string $operator): VirtualSqlConditionSet
	{
		$this->operator = $operator;
		return $this;
	}

	/**
	 * @return VirtualSqlCondition[]|VirtualSqlConditionSet[]
	 */
	public function getConditions(): array
	{
		return $this->conditions;
	}

	/**
	 * @param VirtualSqlCondition[]|VirtualSqlConditionSet[] $conditions
	 * @return VirtualSqlConditionSet
	 */
	public function setConditions(array $conditions): VirtualSqlConditionSet
	{
		$this->conditions = $conditions;
		return $this;
	}

	/**
	 * @param VirtualSqlCondition|VirtualSqlConditionSet $condition
	 * @return $this
	 */
	public function addCondition(VirtualSqlCondition|VirtualSqlConditionSet $condition){
		if($condition instanceof VirtualSqlConditionSet && $condition->getOperator() === $this->getOperator())
		{
			$this->setConditions(array_merge($this->getConditions(),$condition->getConditions()));
		}
		else
		{
			$this->conditions[] = $condition;
		}

		return $this;
	}
}
