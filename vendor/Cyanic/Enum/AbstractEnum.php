<?php

namespace Cyanic\Enum;

use ArrayIterator;
use Cyanic\Enum\EnumInterface;

abstract class AbstractEnum implements EnumInterface
{
	protected static $values;
	
	public static function getValues()
	{
		return static::$values;	 
	}
	
	public static function getValue($key)
	{
		return (static::$values[$key] ? static::$values[$key] : static::$values[0]);	
	}
}