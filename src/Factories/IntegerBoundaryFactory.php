<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Factories;

use Achse\Math\Interval\Boundaries\IntegerBoundary;
use Achse\Math\Interval\Types\Integer;



class IntegerBoundaryFactory
{

	/**
	 * @param int $value
	 * @param bool $state
	 * @return IntegerBoundary
	 */
	public static function create(int $value, bool $state): IntegerBoundary
	{
		return new IntegerBoundary(new Integer($value), $state);
	}

}
