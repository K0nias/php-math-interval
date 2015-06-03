<?php

namespace Achse\Interval\Intervals;

use Achse\Interval\Types\Comparison\IComparable;
use Achse\Interval\Types\Comparison\IntervalUtils;
use Achse\Interval\Types\DateTime;



class DateTimeInterval extends Interval
{

	/**
	 * @inheritdoc
	 */
	public function __construct(IComparable $left, $stateA, IComparable $right, $stateB)
	{
		/** @var DateTime $left */
		$left = DateTime::from($left);
		/** @var DateTime $right */
		$right = DateTime::from($right);

		parent::__construct($left, $stateA, $right, $stateB);
	}



	/**
	 * @param string $since
	 * @param string $till
	 * @return self
	 */
	public static function fromString($since, $till)
	{
		return new static(new DateTime($since), self::CLOSED, new DateTime($till), self::OPENED);
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
		if ($this->getLeft() > $other->getRight()) {
			return FALSE;
		}

		/** @var DateTime $modifiedPlus */
		$modifiedPlus = $this->getRight()->modifyClone("+{$precision}");

		/** @var DateTime $modifiedMinus */
		$modifiedMinus = $other->getLeft()->modifyClone("-{$precision}");

		return (
			$modifiedPlus->greaterThenOrEqual($other->getLeft())
			&& $modifiedPlus->lessThenOrEqual($other->getRight())
			&& $modifiedMinus->lessThenOrEqual($this->getRight())
			&& $modifiedMinus->greaterThenOrEqual($this->getLeft())
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
			IntervalUtils::isSameDate($this->getRight(), $other->getLeft()->modifyClone('-1 day'))
			&& $this->getRight()->format('H:i:s') === '23:59:59'
			&& $other->getLeft()->format('H:i:s') === '00:00:00'
		);
	}



	/**
	 * @return DateTime
	 */
	public function getLeft()
	{
		return parent::getLeft();
	}



	/**
	 * @return DateTime
	 */
	public function getRight()
	{
		return parent::getRight();
	}

}

