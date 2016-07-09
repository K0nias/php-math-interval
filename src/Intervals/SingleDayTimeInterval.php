<?php

declare(strict_types = 1);

namespace Achse\Math\Interval\Intervals;

use Achse\Comparable\IComparable;
use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Boundaries\DateTimeBoundary;
use Achse\Math\Interval\Boundaries\SingleDayTimeBoundary;
use Achse\Math\Interval\ModificationNotPossibleException;
use Achse\Math\Interval\Types\DateTime;
use Achse\Math\Interval\Types\SingleDayTime;
use Achse\Math\Interval\Utils\IntervalUtils;
use Nette\InvalidArgumentException;



class SingleDayTimeInterval extends Interval
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
	public static function fromString(string $from, string $till) : SingleDayTimeInterval
	{
		return new static(
			new SingleDayTimeBoundary(SingleDayTime::from($from), Boundary::CLOSED),
			new SingleDayTimeBoundary(SingleDayTime::from($till), Boundary::OPENED)
		);
	}



	/**
	 * @param DateTimeInterval $interval
	 * @param \DateTime $date
	 * @return static
	 */
	public static function fromDateTimeInterval(DateTimeInterval $interval, \DateTime $date) : SingleDayTimeInterval
	{
		$thisDayInterval = self::buildWholeDayInterval($date);

		/** @var DateTimeInterval $intersection */
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
	 * @param SingleDayTimeInterval $other
	 * @param string $precision
	 * @return bool
	 */
	public function isFollowedBy(
		SingleDayTimeInterval $other,
		string $precision = IntervalUtils::PRECISION_ON_SECOND
	) : bool
	{
		try {
			$this->getRight()->getValue()->modifyClone("+{$precision}");
			$other->getLeft()->getValue()->modifyClone("-{$precision}");
		} catch (ModificationNotPossibleException $e) {
			return FALSE;
		}


		$dummyDay = new DateTime(SingleDayTime::INTERNAL_DATE); // intentionally using @internal

		return $this->toDateTimeInterval($dummyDay)->isFollowedBy($other->toDateTimeInterval($dummyDay));
	}



	/**
	 * @return SingleDayTimeBoundary
	 */
	public function getLeft() : Boundary
	{
		return parent::getLeft();
	}



	/**
	 * @return SingleDayTimeBoundary
	 */
	public function getRight() : Boundary
	{
		return parent::getRight();
	}



	/**
	 * @param \DateTime $day
	 * @return DateTimeInterval
	 */
	public function toDateTimeInterval(\DateTime $day) : DateTimeInterval
	{
		$left = new DateTimeBoundary(
			$this->getLeft()->getValue()->toDateTime($day), $this->getLeft()->getState()
		);
		$right = new DateTimeBoundary(
			$this->getRight()->getValue()->toDateTime($day), $this->getRight()->getState()
		);

		return new DateTimeInterval($left, $right);
	}



	/**
	 * @param IComparable $element
	 * @param bool $state
	 * @return SingleDayTimeBoundary
	 */
	protected function buildBoundary(IComparable $element, bool $state) : Boundary
	{
		return new SingleDayTimeBoundary($element, $state);
	}



	/**
	 * @param \DateTime $date
	 * @return DateTimeInterval
	 */
	protected static function buildWholeDayInterval(\DateTime $date) : DateTimeInterval
	{
		/** @var DateTime $start */
		$start = DateTime::from($date);
		$start->setTime(0, 0, 0);

		/** @var DateTime $ends */
		$ends = DateTime::from($date);
		$ends->setTime(23, 59, 59);

		$thisDayInterval = new DateTimeInterval(
			new DateTimeBoundary($start, Boundary::CLOSED),
			new DateTimeBoundary($ends, Boundary::CLOSED)
		);

		return $thisDayInterval;
	}

}

