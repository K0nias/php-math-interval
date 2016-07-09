<?php

declare(strict_types = 1);

namespace Achse\Math\Interval\Factories;

use Achse\Math\Interval\Intervals\IntegerInterval;
use Nette\Object;



class IntegerIntervalFactory extends Object
{

	/**
	 * @param int $leftElement
	 * @param bool $leftState
	 * @param int $rightElement
	 * @param bool $rightState
	 * @return IntegerInterval
	 */
	public static function create(
		int $leftElement,
		bool $leftState,
		int $rightElement,
		bool $rightState
	) : IntegerInterval
	{
		return new IntegerInterval(
			IntegerBoundaryFactory::create($leftElement, $leftState),
			IntegerBoundaryFactory::create($rightElement, $rightState)
		);
	}

}
