<?php

declare(strict_types=1);

namespace Achse\Math\Interval\DateTime;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\Interval;
use Achse\Math\Interval\IntervalUtils;



/**
 * @deprecated Use DateTimeImmutable, always!
 */
final class DateTimeInterval extends Interval
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
	public function isFollowedByWithPrecision(DateTimeInterval $other, string $precision): bool
	{
		if ($this->getLeft() > $other->getRight()) { // intentionally compares boundaries
			return FALSE;
		}

		/** @var DateTime $modifiedPlus */
		$modifiedPlus = clone $this->getRight()->getValue();
		$modifiedPlus = $modifiedPlus->modify("+{$precision}");

		/** @var DateTime $modifiedMinus */
		$modifiedMinus = clone $other->getLeft()->getValue();
		$modifiedMinus = $modifiedMinus->modify("-{$precision}");

		return (
			$modifiedPlus->isGreaterThanOrEqual($other->getLeft()->getValue())
			&& $modifiedPlus->isLessThanOrEqual($other->getRight()->getValue())
			&& $modifiedMinus->isLessThanOrEqual($this->getRight()->getValue())
			&& $modifiedMinus->isGreaterThanOrEqual($this->getLeft()->getValue())
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
		$left = clone $other->getLeft()->getValue();

		return (
			IntervalUtils::isSameDate($this->getRight()->getValue(), $left->modify('-1 day'))
			&& $this->getRight()->getValue()->format('H:i:s') === '23:59:59'
			&& $other->getLeft()->getValue()->format('H:i:s') === '00:00:00'
		);
	}



	/**
	 * @return string
	 */
	public function __toString(): string
	{
		/** @var DateTime $left */
		$left = $this->getLeft()->getValue();
		/** @var DateTime $right */
		$right = $this->getRight()->getValue();

		return (
			$this->getLeftBracket() . $left->format('Y-m-d H:i:s')
			. self::STRING_DELIMITER . ' '
			. $right->format('Y-m-d H:i:s') . $this->getRightBracket()
		);
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

