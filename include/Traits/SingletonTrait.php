<?php

namespace VirtualSql\Traits;

trait SingletonTrait
{
	/**
	 * The single instance of the class.
	 *
	 * @var static
	 */
	protected static $instance = null;

	/**
	 * Get class instance.
	 *
	 * @return static Instance.
	 */
	final public static function getInstance(): static
	{
		if ( null === static::$instance )
			static::$instance = new static();

		return static::$instance;
	}
}

