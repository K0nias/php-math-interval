<?php

namespace Achse\Math\Interval\Types;

use Achse\Math\Interval\Types\Comparison\ComparisonMethods;
use Achse\Math\Interval\Types\Comparison\IComparable;
use Achse\Math\Interval\Utils\IntervalUtils;
use LogicException;
use Nette\Utils\DateTime as NDateTime;



class DateTime extends NDateTime implements IComparable
{

	use ComparisonMethods;



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other)
	{
		if (!$other instanceof static) {
			throw new LogicException('You cannot compare sheep with the goat.');
		}

		return IntervalUtils::intCmp($this->getTimestamp(), $other->getTimestamp());
	}



	/**
	 * @inheritdoc
	 * @return static
	 */
	public static function from($time)
	{
		// Intentionally: This method is here just because of 'static' annotation for return
		return parent::from($time);
	}

}
