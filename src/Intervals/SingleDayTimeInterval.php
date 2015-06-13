<?php

namespace Achse\Math\Interval\Intervals;

use Achse\Math\Interval\ModificationNotPossibleException;
use Achse\Math\Interval\Types\Comparison\IComparable;
use Achse\Math\Interval\Types\Comparison\IntervalUtils;
use Achse\Math\Interval\Types\DateTime;
use Achse\Math\Interval\Types\SingleDayTime;
use Nette\InvalidArgumentException;



/**
 * Classes like this are here because of absence generic in PHP
 * so this provides a tool to work with concrete IComparable type.
 */
class SingleDayTimeInterval extends Interval
{

	/**
	 * @param string $from
	 * @param string $till
	 * @return SingleDayTimeInterval
	 */
	public static function fromString($from, $till)
	{
		return new static(
			SingleDayTime::from($from), Interval::CLOSED, SingleDayTime::from($till), Interval::OPENED
		);
	}



	/**
	 * @param DateTimeInterval $interval
	 * @param \DateTime $date
	 * @return static
	 */
	public static function fromDateTimeInterval(DateTimeInterval $interval, \DateTime $date)
	{
		/** @var DateTime $start */
		$start = DateTime::from($date);
		$start->setTime(0, 0, 0);

		/** @var DateTime $ends */
		$ends = DateTime::from($date);
		$ends->setTime(23, 59, 59);

		$thisDayInterval = new DateTimeInterval($start, Interval::CLOSED, $ends, Interval::OPENED);

		/** @var DateTimeInterval $intersection */
		$intersection = $interval->getIntersection($thisDayInterval);

		if ($intersection === NULL) {
			throw new InvalidArgumentException('Given day does not hits given interval. No intersection possible.');
		}

		return new static(
			$intersection->getLeft(), $intersection->getLeftState(), $intersection->getRight(), $intersection->getRightState()
		);
	}



	/**
	 * @inheritdoc
	 */
	public function isFollowedBy(SingleDayTimeInterval $other, $precision = IntervalUtils::PRECISION_ON_SECOND)
	{
		try {
			$this->getRight()->modifyClone("+{$precision}");
			$other->getLeft()->modifyClone("-{$precision}");
		} catch (ModificationNotPossibleException $e) {
			return FALSE;
		}


		$dummyDay = new DateTime(SingleDayTime::INTERNAL_DATE); // intentionally using @internal

		return $this->toDateTimeInterval($dummyDay)->isFollowedBy($other->toDateTimeInterval($dummyDay));
	}



	/**
	 * @return SingleDayTime
	 */
	public function getLeft()
	{
		return parent::getLeft();
	}



	/**
	 * @return SingleDayTime
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
		return new DateTimeInterval(
			$this->getLeft()->toDateTime($day), $this->getLeftState(),
			$this->getRight()->toDateTime($day), $this->getRightState()
		);
	}

}

