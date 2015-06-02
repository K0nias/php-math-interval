<?php

namespace Achse\Interval\Types;

use Achse\Interval\Types\Comparison\ComparisonMethods;
use Achse\Interval\Types\Comparison\IComparable;
use Achse\Interval\Types\Comparison\Utils;
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
			throw new \LogicException('You cannot compare sheep with the goat.');
		}

		return Utils::intCmp($this->getTimestamp(), $other->getTimestamp());
	}

}
