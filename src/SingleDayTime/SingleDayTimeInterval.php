<?php

declare(strict_types=1);

namespace Achse\Math\Interval\SingleDayTime;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable;
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutableBoundary;
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutableInterval;
use Achse\Math\Interval\Interval;
use Achse\Math\Interval\IntervalRangesInvalidException;
use Achse\Math\Interval\ModificationNotPossibleException;
use DateTimeInterface;



final class SingleDayTimeInterval extends Interval
{

	/**
	 * @inheritdoc
	 */
	public function __construct(Boundary $left, Boundary $right)
	{
		$this->validateBoundaryChecks($left, $right, SingleDayTimeBoundary::class);

		if ($left->isGreaterThan($right) && !$this->isEndingAtMidnightNextDay($left, $right)) {
			throw new IntervalRangesInvalidException('Left endpoint cannot be greater then Right endpoint.');
		}

		$this->left = $left;
		$this->right = $right;

		// intentionally, no parent constructor calling
	}



	/**
	 * @inheritdoc
	 */
	protected function isContainingElementRightCheck(IComparable $element): bool
	{
		return $this->isRightOpened() && $this->getRight()->getValue()->isGreaterThan($element)
			|| $this->isRightClosed() && $this->getRight()->getValue()->isGreaterThanOrEqual($element)
			|| $this->isEndingAtMidnightNextDay($this->left, $this->right);
	}



	/**
	 * @param string $from
	 * @param string $till
	 * @return SingleDayTimeInterval
	 */
	public static function fromString(string $from, string $till): SingleDayTimeInterval
	{
		return new static(
			new SingleDayTimeBoundary(SingleDayTime::from($from), Boundary::CLOSED),
			new SingleDayTimeBoundary(SingleDayTime::from($till), Boundary::OPENED)
		);
	}



	/**
	 * @param DateTimeImmutableInterval $interval
	 * @param DateTimeInterface $date
	 * @return static
	 */
	public static function fromDateTimeInterval(
		DateTimeImmutableInterval $interval,
		DateTimeInterface $date
	): SingleDayTimeInterval {
		$thisDayInterval = self::buildWholeDayInterval($date);

		/** @var DateTimeImmutableInterval $intersection */
		$intersection = $thisDayInterval->intersection($interval);

		$left = SingleDayTime::fromDateTime($intersection->getLeft()->getValue());
		$right = SingleDayTime::fromDateTime($intersection->getRight()->getValue());

		return new static(
			new SingleDayTimeBoundary($left, $intersection->getLeft()->getState()),
			new SingleDayTimeBoundary($right, $intersection->getRight()->getState())
		);
	}



	/**
	 * @param DateTimeInterface $date
	 * @return DateTimeImmutableInterval
	 */
	protected static function buildWholeDayInterval(DateTimeInterface $date): DateTimeImmutableInterval
	{
		$start = DateTimeImmutable::from($date)->setTime(0, 0, 0);
		$ends = DateTimeImmutable::from($date)->setTime(23, 59, 59);

		$thisDayInterval = new DateTimeImmutableInterval(
			new DateTimeImmutableBoundary($start, Boundary::CLOSED),
			new DateTimeImmutableBoundary($ends, Boundary::CLOSED)
		);

		return $thisDayInterval;
	}



	/**
	 * @param SingleDayTimeInterval $other
	 * @param string $precision
	 * @return bool
	 */
	public function isFollowedByWithPrecision(Interval $other, $precision): bool
	{
		try {
			$this->getRight()->getValue()->modify('+' . $precision);
			$other->getLeft()->getValue()->modify('-' . $precision);
		} catch (ModificationNotPossibleException $e) {
			return FALSE;
		}

		$dummyDay = new DateTimeImmutable('2001-01-01 00:00:00');

		return $this->toDateTimeInterval($dummyDay)->isFollowedByWithPrecision(
			$other->toDateTimeInterval($dummyDay),
			$precision
		);
	}



	/**
	 * @return SingleDayTimeBoundary
	 */
	public function getRight(): Boundary
	{
		return parent::getRight();
	}



	/**
	 * @return SingleDayTimeBoundary
	 */
	public function getLeft(): Boundary
	{
		return parent::getLeft();
	}



	/**
	 * @param DateTimeInterface $day
	 * @return DateTimeImmutableInterval
	 */
	public function toDateTimeInterval(DateTimeInterface $day): DateTimeImmutableInterval
	{
		$left = new DateTimeImmutableBoundary(
			$this->getLeft()->getValue()->toDateTime($day), $this->getLeft()->getState()
		);
		$right = new DateTimeImmutableBoundary(
			$this->getRight()->getValue()->toDateTime($day), $this->getRight()->getState()
		);

		return new DateTimeImmutableInterval($left, $right);
	}



	/**
	 * @param IComparable $element
	 * @param bool $state
	 * @return SingleDayTimeBoundary
	 */
	protected function buildBoundary(IComparable $element, bool $state): Boundary
	{
		return new SingleDayTimeBoundary($element, $state);
	}



	/**
	 * @param Boundary $left
	 * @param Boundary $right
	 * @return bool
	 */
	private function isEndingAtMidnightNextDay(Boundary $left, Boundary $right): bool
	{
		return !($left->isOpened() && $left->getValue()->isEqual($right))
			&& $right->isOpened()
			&& $right->getValue()->isEqual($this->getZeroElement());
	}



	/**
	 * @return SingleDayTime
	 */
	private function getZeroElement(): SingleDayTime
	{
		return new SingleDayTime(0, 0, 0);
	}

}

