<?php

namespace Achse\Math\Interval\Factories;

use Achse\Math\Interval\Boundaries\IntegerBoundary;
use Achse\Math\Interval\Types\Integer;
use Nette\InvalidArgumentException;
use Nette\Object;



class IntegerBoundaryFactory extends Object
{

	/**
	 * @param int $value
	 * @param bool $state
	 * @return IntegerBoundary
	 */
	public static function create($value, $state)
	{
		if (!is_int($value)) {
			throw new InvalidArgumentException(
				"Given value must be integer, but: '{$value}' (" . gettype($value) . ") given."
			);
		}

		return new IntegerBoundary(new Integer($value), $state);
	}

}
