<?php

namespace VirtualSql\Query\Element\ConditionValue;

use JetBrains\PhpStorm\Pure;
use VirtualSql\Exceptions\InvalidQueryPartException;

class VirtualSqlNumberConditionValue extends VirtualSqlConditionValue
{
	/**
	 * @var float|int
	 */
	private int|float $number;

	/**
	 * @param int|float $number
	 * @param bool $parseToFloat
	 * @throws InvalidQueryPartException
	 */
	public function __construct(int|float $number, bool $parseToFloat = false)
	{
		if(is_string($number) && is_numeric($number))
			$number = $parseToFloat ? (float)$number : (int)$number;

		if(!is_numeric($number))
			throw new InvalidQueryPartException('Value "'.$number.'" is not a number');

		$this->number = $number;
	}

	/**
	 * @return float|int
	 */
	public function getNumber(): float|int
	{
		return $this->number;
	}

	/**
	 * @return float|int
	 */
	#[Pure] public function getValue(): float|int
	{
		return $this->getNumber();
	}
}
