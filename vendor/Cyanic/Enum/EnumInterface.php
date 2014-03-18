<?php

namespace Cyanic\Enum;

interface EnumInterface
{
	/**
	 * 
	 * Get values
	 * 
	 * @return array
	 * 
	 */
	public static function getValues();
	
	
	/**
	 * 
	 * Get Value
	 * 
	 * @param string $key
	 * @return string $value
	 * 
	 */
	public static function getValue($key);
}