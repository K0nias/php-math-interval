<?php

namespace Achse\Interval\Types;

use Achse\Interval\Types\Comparison\ComparisonMethods;
use Achse\Interval\Types\Comparison\IComparable;
use Achse\Interval\Types\Comparison\Utils;
use Nette\Object;



class Integer extends Object implements IComparable
{

	use ComparisonMethods;

	/**
	 * @var int
	 */
	private $internal;



	/**
	 * @param int $internal
	 */
	public function __construct($internal)
	{
		$this->internal = $internal;
	}



	/**
	 * @return int
	 */
	public function toInt()
	{
		return $this->internal;
	}



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other)
	{
		if (!$other instanceof static) {
			throw new \LogicException('You cannot compare sheep with the goat.');
		}

		return Utils::intCmp($this->internal, $other->toInt());
	}
}
