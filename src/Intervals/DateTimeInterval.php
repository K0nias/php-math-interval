<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Intervals;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Boundaries\DateTimeBoundary;
use Achse\Math\Interval\Types\DateTime;
use Achse\Math\Interval\Utils\IntervalUtils;



class DateTimeInterval extends Interval
{

	/**
	 * @inheritdoc
	 */
	public function __construct(Boundary $left, Boundary $right)
	{
		$this->validateBoundaryChecks($left, $right, DateTimeBoundary::class);

		parent::__construct($left, $right);
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
	public function isFollowedBy(DateTimeInterval $other, string $precision = IntervalUtils::PRECISION_ON_SECOND): bool
	{
		if ($this->getLeft() > $other->getRight()) { // intentionally compares boundaries
			return FALSE;
		}

		/** @var DateTime $modifiedPlus */
		$modifiedPlus = $this->getRight()->getValue()->modifyClone("+{$precision}");

		/** @var DateTime $modifiedMinus */
		$modifiedMinus = $other->getLeft()->getValue()->modifyClone("-{$precision}");

		return (
			$modifiedPlus->isGreaterThanOrEqual($other->getLeft()->getValue())
			&& $modifiedPlus->isLessThanOrEqual($other->getRight()->getValue())
			&& $modifiedMinus->isLessThanOrEqual($this->getRight()->getValue())
			&& $modifiedMinus->isGreaterThanOrEqual($this->getLeft()->getValue())
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
	public function isFollowedByAtMidnight(DateTimeInterval $other): bool
	{
		return (
			IntervalUtils::isSameDate(
				$this->getRight()->getValue(),
				$other->getLeft()->getValue()
					->modifyClone('-1 day')
			)
			&& $this->getRight()->getValue()->format('H:i:s') === '23:59:59'
			&& $other->getLeft()->getValue()->format('H:i:s') === '00:00:00'
		);
	}



	/**
	 * @return DateTimeBoundary
	 */
	public function getLeft(): Boundary
	{
		return parent::getLeft();
	}



	/**
	 * @return DateTimeBoundary
	 */
	public function getRight(): Boundary
	{
		return parent::getRight();
	}



	/**
	 * @param IComparable $element
	 * @param bool $state
	 * @return DateTimeBoundary
	 */
	protected function buildBoundary(IComparable $element, bool $state): Boundary
	{
		return new DateTimeBoundary($element, $state);
	}

}

