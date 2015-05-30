<?php

namespace Achse\Interval\Intervals;

use Achse\Interval\Types\Comparison\IComparable;



/**
 * NotEmptyInterval is proper when is NOT empty NOT degenerate. (Degenerate intervals contains single element, A = B).
 *
 * Left-closed, right opened means: [A, B).
 *      - Contains all elements that are grater or equal than A and less then B.
 *      - Program condition: (A <= x && x < B)
 *
 */
class LeftClosedRightOpenedProperNotEmptyInterval extends NotEmptyInterval
{

	protected function validateRange(IComparable $a, IComparable $b)
	{
		// Intentionally not parent call
		if ($a->greaterThenOrEqual($b)) {
			throw new \LogicException('In proper interval A must be less then B (A < B).');
		}

	}

}
