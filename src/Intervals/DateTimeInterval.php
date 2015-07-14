<?php

namespace Achse\Math\Interval\Intervals;

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Boundaries\DateTimeBoundary;
use Achse\Math\Interval\Factories\DateTimeBoundaryFactory;
use Achse\Math\Interval\Utils\IntervalUtils;
use Achse\Math\Interval\Types\DateTime;
use Nette\InvalidArgumentException;



class DateTimeInterval extends Interval
{

	/**
	 * @inheritdoc
	 */
	public function __construct(Boundary $left, Boundary $right)
	{
		if (!($left instanceof DateTimeBoundary)) {
			throw new InvalidArgumentException('\$left have to be instance of ' . DateTimeBoundary::class);
		}

		if (!($right instanceof DateTimeBoundary)) {
			throw new InvalidArgumentException('\$right have to be instance of ' . DateTimeBoundary::class);
		}

		parent::__construct($left, $right);
	}



	/**
	 * @param string $since
	 * @param string $till
	 * @return self
	 */
	public static function fromString($since, $till)
	{
		return new static(
			DateTimeBoundaryFactory::create($since, Boundary::CLOSED),
			DateTimeBoundaryFactory::create($till, Boundary::OPENED)
		);
	}



	/**
	 * # Example:
	 *
	 *     This:  □□□□□□■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *
	 * # False examples:
	 *
	 *     This:  □□□□□□■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□
	 *
	 *     This:  □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□□□□□
	 *
	 * @param DateTimeInterval $other
	 * @param string $precision
	 * @return bool
	 */
	public function isFollowedBy(DateTimeInterval $other, $precision = IntervalUtils::PRECISION_ON_SECOND)
	{
		if ($this->getLeft() > $other->getRight()) { // intentionally compares boundaries
			return FALSE;
		}

		/** @var DateTime $modifiedPlus */
		$modifiedPlus = $this->getRight()->getValue()->modifyClone("+{$precision}");

		/** @var DateTime $modifiedMinus */
		$modifiedMinus = $other->getLeft()->getValue()->modifyClone("-{$precision}");

		return (
			$modifiedPlus->isGreaterThenOrEqual($other->getLeft()->getValue())
			&& $modifiedPlus->isLessThenOrEqual($other->getRight()->getValue())
			&& $modifiedMinus->isLessThenOrEqual($this->getRight()->getValue())
			&& $modifiedMinus->isGreaterThenOrEqual($this->getLeft()->getValue())
		);
	}



	/**
	 * # Example:
	 *
	 *     This:  □□□□□□■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *                    23:59:59 >< 00:00:00
	 *
	 * @param DateTimeInterval $other
	 * @return bool
	 */
	public function isFollowedByAtMidnight(DateTimeInterval $other)
	{
		return (
			IntervalUtils::isSameDate($this->getRight()->getValue(), $other->getLeft()->getValue()->modifyClone('-1 day'))
			&& $this->getRight()->getValue()->format('H:i:s') === '23:59:59'
			&& $other->getLeft()->getValue()->format('H:i:s') === '00:00:00'
		);
	}



	/**
	 * @return DateTimeBoundary
	 */
	public function getLeft()
	{
		return parent::getLeft();
	}



	/**
	 * @return DateTimeBoundary
	 */
	public function getRight()
	{
		return parent::getRight();
	}

}

