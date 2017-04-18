<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTimeImmutable;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\Interval;
use Achse\Math\Interval\IntervalUtils;



final class DateTimeImmutableInterval extends Interval
{

	/**
	 * @inheritdoc
	 */
	public function __construct(Boundary $left, Boundary $right)
	{
		$this->validateBoundaryChecks($left, $right, DateTimeImmutableBoundary::class);

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
	 * @param DateTimeImmutableInterval $other
	 * @param string $precision
	 * @return bool
	 */
	public function isFollowedByWithPrecision(DateTimeImmutableInterval $other, string $precision): bool
	{
		if ($this->getLeft() > $other->getRight()) { // intentionally compares boundaries
			return FALSE;
		}

		/** @var DateTimeImmutable $modifiedPlus */
		$modifiedPlus = $this->getRight()->getValue()->modify("+{$precision}");

		/** @var DateTimeImmutable $modifiedMinus */
		$modifiedMinus = $other->getLeft()->getValue()->modify("-{$precision}");

		return (
			$modifiedPlus->isGreaterThanOrEqual($other->getLeft()->getValue())
			&& $modifiedPlus->isLessThanOrEqual($other->getRight()->getValue())
			&& $modifiedMinus->isLessThanOrEqual($this->getRight()->getValue())
			&& $modifiedMinus->isGreaterThanOrEqual($this->getLeft()->getValue())
		);
	}



	/**
	 * @return DateTimeImmutableBoundary
	 */
	public function getLeft(): Boundary
	{
		return parent::getLeft();
	}



	/**
	 * @return DateTimeImmutableBoundary
	 */
	public function getRight(): Boundary
	{
		return parent::getRight();
	}



	/**
	 * # Example:
	 *
	 *     This:  □□□□□□■■■■■■■■■■■■□□□□□□□□□□□□□□□□□□□□□□□□□□□
	 *     Other: □□□□□□□□□□□□□□□□□□■■■■■■■■■■■□□□□□□□□□□□□□□□□
	 *                    23:59:59 >< 00:00:00
	 *
	 * @param DateTimeImmutableInterval $other
	 * @return bool
	 */
	public function isFollowedByAtMidnight(DateTimeImmutableInterval $other): bool
	{
		return (
			IntervalUtils::isSameDate(
				$this->getRight()->getValue(),
				$other->getLeft()->getValue()->modify('-1 day')
			)
			&& $this->getRight()->getValue()->format('H:i:s') === '23:59:59'
			&& $other->getLeft()->getValue()->format('H:i:s') === '00:00:00'
		);
	}



	/**
	 * @param IComparable $element
	 * @param bool $state
	 * @return DateTimeImmutableBoundary
	 */
	protected function buildBoundary(IComparable $element, bool $state): Boundary
	{
		return new DateTimeImmutableBoundary($element, $state);
	}

}

