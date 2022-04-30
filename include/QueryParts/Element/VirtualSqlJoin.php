<?php

namespace VirtualSql\QueryParts\Element;

use VirtualSql\Definition\VirtualSqlColumn;
use VirtualSql\Exceptions\InvalidQueryPartException;
use VirtualSql\VirtualSqlConstant;

class VirtualSqlJoin
{
	public static array $acceptedJoinTypes = [
		VirtualSqlConstant::JOIN_TYPE_INNER => VirtualSqlConstant::JOIN_TYPE_INNER,
		VirtualSqlConstant::JOIN_TYPE_OUTER => VirtualSqlConstant::JOIN_TYPE_OUTER,
		VirtualSqlConstant::JOIN_TYPE_LEFT => VirtualSqlConstant::JOIN_TYPE_LEFT,
		VirtualSqlConstant::JOIN_TYPE_RIGHT => VirtualSqlConstant::JOIN_TYPE_RIGHT,
	];

	/**
	 * @var string
	 */
	private string $type;

	/**
	 * @var VirtualSqlColumn
	 */
	private VirtualSqlColumn $fromColumn;

	/**
	 * @var VirtualSqlColumn
	 */
	private VirtualSqlColumn $toColumn;

	/**
	 * @var VirtualSqlConditionSet|null
	 */
	private VirtualSqlConditionSet|null $conditionSet;

	/**
	 * @param string $type
	 * @param VirtualSqlColumn $fromColumn
	 * @param VirtualSqlColumn $toColumn
	 * @param VirtualSqlConditionSet|null $conditionSet
	 * @throws InvalidQueryPartException
	 */
	public function __construct(string $type, VirtualSqlColumn $fromColumn, VirtualSqlColumn $toColumn, ?VirtualSqlConditionSet $conditionSet = null)
	{
		$this->setType($type);
		$this->fromColumn = $fromColumn;
		$this->toColumn = $toColumn;
		$this->conditionSet = $conditionSet;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return VirtualSqlJoin
	 * @throws InvalidQueryPartException
	 */
	public function setType(string $type): VirtualSqlJoin
	{
		if(isset(self::$acceptedJoinTypes[$type]) === false)
			throw new InvalidQueryPartException('"'.$type.'" is not a valid join type');

		$this->type = $type;
		return $this;
	}

	/**
	 * @return VirtualSqlColumn
	 */
	public function getFromColumn(): VirtualSqlColumn
	{
		return $this->fromColumn;
	}

	/**
	 * @param VirtualSqlColumn $fromColumn
	 * @return VirtualSqlJoin
	 */
	public function setFromColumn(VirtualSqlColumn $fromColumn): VirtualSqlJoin
	{
		$this->fromColumn = $fromColumn;
		return $this;
	}

	/**
	 * @return VirtualSqlColumn
	 */
	public function getToColumn(): VirtualSqlColumn
	{
		return $this->toColumn;
	}

	/**
	 * @param VirtualSqlColumn $toColumn
	 * @return VirtualSqlJoin
	 */
	public function setToColumn(VirtualSqlColumn $toColumn): VirtualSqlJoin
	{
		$this->toColumn = $toColumn;
		return $this;
	}

	/**
	 * @return VirtualSqlConditionSet|null
	 */
	public function getConditionSet(): ?VirtualSqlConditionSet
	{
		return $this->conditionSet;
	}

	/**
	 * @param VirtualSqlConditionSet|null $conditionSet
	 * @return VirtualSqlJoin
	 */
	public function setConditionSet(?VirtualSqlConditionSet $conditionSet): VirtualSqlJoin
	{
		$this->conditionSet = $conditionSet;
		return $this;
	}
}
