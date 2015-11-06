<?php

namespace Achse\Math\Interval\Intervals;

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\Boundaries\DateTimeBoundary;
use Achse\Math\Interval\Boundaries\SingleDayTimeBoundary;
use Achse\Math\Interval\ModificationNotPossibleException;
use Achse\Math\Interval\Utils\IntervalUtils;
use Achse\Math\Interval\Types\DateTime;
use Achse\Math\Interval\Types\SingleDayTime;
use Nette\InvalidArgumentException;
use Achse\Math\Interval\Types\Comparison\IComparable;



class SingleDayTimeInterval extends Interval
{

	/**
	 * @inheritdoc
	 */
	public function __construct(Boundary $left, Boundary $right)
	{
		if (!($left instanceof SingleDayTimeBoundary)) {
			throw new InvalidArgumentException('\$left have to be instance of ' . SingleDayTimeBoundary::class . '.');
		}

		if (!($right instanceof SingleDayTimeBoundary)) {
			throw new InvalidArgumentException('\$right have to be instance of ' . SingleDayTimeBoundary::class . '.');
		}

		parent::__construct($left, $right);
	}



	/**
	 * @param string $from
	 * @param string $till
	 * @return SingleDayTimeInterval
	 */
	public static function fromString($from, $till)
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
	public static function fromDateTimeInterval(DateTimeInterval $interval, \DateTime $date)
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
	 * @inheritdoc
	 */
	public function isFollowedBy(SingleDayTimeInterval $other, $precision = IntervalUtils::PRECISION_ON_SECOND)
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
	public function getLeft()
	{
		return parent::getLeft();
	}



	/**
	 * @return SingleDayTimeBoundary
	 */
	public function getRight()
	{
		return parent::getRight();
	}



	/**
	 * @param \DateTime $day
	 * @return DateTimeInterval
	 */
	public function toDateTimeInterval(\DateTime $day)
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
	protected function buildBoundary(IComparable $element, $state)
	{
		return new SingleDayTimeBoundary($element, $state);
	}



	/**
	 * @param \DateTime $date
	 * @return DateTimeInterval
	 */
	protected static function buildWholeDayInterval(\DateTime $date)
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

