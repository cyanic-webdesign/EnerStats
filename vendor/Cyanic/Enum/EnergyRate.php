<?php

namespace Cyanic\Enum;

final class EnergyRate extends AbstractEnum
{
	const UNKNOWN = 0;
	const DAY = 1;
	const NIGHT = 2;
		
	protected static $values = array(
		self::UNKNOWN => 'Onbekend',
		self::DAY => 'Dagstroom',
		self::NIGHT => 'Nachtstroom'
	);
}