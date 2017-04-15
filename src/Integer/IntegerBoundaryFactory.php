<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Integer;



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
