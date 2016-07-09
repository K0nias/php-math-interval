<?php

namespace Achse\Math\Interval\Types;

use Achse\Comparable\ComparisonMethods;
use Achse\Comparable\IComparable;
use Achse\Math\Interval\Utils\IntervalUtils;
use LogicException;
use Nette\InvalidArgumentException;
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
	public function compare(IComparable $other) : int
	{
		if (!$other instanceof static) {
			throw new LogicException('You cannot compare sheep with the goat.');
		}

		return IntervalUtils::intCmp($this->internal, $other->toInt());
	}



	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->toInt();
	}



	public static function fromString($string)
	{
		if (!is_numeric($string)) {
			throw new InvalidArgumentException("'$string' in not numeric.");
		}

		return new static((int) $string);
	}
}
