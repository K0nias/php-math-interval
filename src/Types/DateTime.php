<?php

namespace Achse\Interval\Types;

use Achse\Interval\Types\Comparison\ComparisonMethods;
use Achse\Interval\Types\Comparison\IComparable;
use Nette\Utils\DateTime as NDateTime;



class DateTime extends NDateTime implements IComparable
{

	use ComparisonMethods;

	/**
	 * @var NDateTime
	 */
	private $dateTime;



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other)
	{
		if (!$other instanceof DateTime) {
			throw new \LogicException('You cannot compare sheep with the goat.');
		}

		if ($this->getTimestamp() === $other->getTimestamp()) {
			return IComparable::THIS_EQUAL_AS_OTHER;

		} elseif ($this > $other) {
			return IComparable::THIS_GREATER_THEN_OTHER;

		} else {
			return IComparable::THIS_LESS_THEN_OTHER;
		}
	}

}
