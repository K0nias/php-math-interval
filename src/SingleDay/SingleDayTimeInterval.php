<?php

declare(strict_types=1);

namespace Achse\Math\Interval\SingleDay;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Boundary;
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutable;
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutableBoundary;
use Achse\Math\Interval\DateTimeImmutable\DateTimeImmutableInterval;
use Achse\Math\Interval\Interval;
use Achse\Math\Interval\IntervalUtils;
use Achse\Math\Interval\ModificationNotPossibleException;
use DateTimeInterface;
use InvalidArgumentException;



final class SingleDayTimeInterval extends Interval
{

	/**
	 * @inheritdoc
	 */
	public function __construct(Boundary $left, Boundary $right)
	{
		$this->validateBoundaryChecks($left, $right, SingleDayTimeBoundary::class);

		parent::__construct($left, $right);
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
		$intersection = $thisDayInterval->getIntersection($interval);

		if ($intersection === NULL) {
			throw new InvalidArgumentException('Given day does not hits given interval. No intersection possible.');
		}

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
	public function isFollowedBy(
		SingleDayTimeInterval $other,
		string $precision = IntervalUtils::PRECISION_ON_SECOND
	): bool {
		try {
			$this->getRight()->getValue()->modifyClone("+{$precision}");
			$other->getLeft()->getValue()->modifyClone("-{$precision}");
		} catch (ModificationNotPossibleException $e) {
			return FALSE;
		}


		$dummyDay = new DateTimeImmutable('2001-01-01 00:00:00');

		return $this->toDateTimeInterval($dummyDay)->isFollowedBy($other->toDateTimeInterval($dummyDay));
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

}

