<?php

namespace Achse\Math\Interval\Types;

use Achse\Math\Interval\ModificationNotPossibleException;
use Achse\Math\Interval\Types\Comparison\ComparisonMethods;
use Achse\Math\Interval\Types\Comparison\IComparable;
use Achse\Math\Interval\Utils\IntervalUtils;
use LogicException;
use Nette\InvalidArgumentException;
use Nette\Object;



class SingleDayTime extends Object implements IComparable
{

	use ComparisonMethods;

	/**
	 * @internal
	 */
	const INTERNAL_DATE = '2000-01-01';

	/**
	 * @var int
	 */
	private $hours = 0;

	/**
	 * @var int
	 */
	private $minutes = 0;

	/**
	 * @var float
	 */
	private $seconds = 0.0;



	/**
	 * @param int $hours
	 * @param int $minutes
	 * @param float $seconds
	 */
	public function __construct($hours, $minutes, $seconds)
	{
		$this->setHours($hours);
		$this->setMinutes($minutes);
		$this->setSeconds($seconds);
	}



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other)
	{
		if (!$other instanceof static) {
			throw new LogicException('You cannot compare sheep with the goat.');
		}

		return IntervalUtils::intCmp($this->toSeconds(), $other->toSeconds());
	}



	/**
	 * @return float
	 */
	public function toSeconds()
	{
		return $this->hours * 3600 + $this->minutes * 60 + $this->seconds;
	}



	/**
	 * @return int
	 */
	public function getHours()
	{
		return $this->hours;
	}



	/**
	 * @return int
	 */
	public function getMinutes()
	{
		return $this->minutes;
	}



	/**
	 * @return float
	 */
	public function getSeconds()
	{
		return $this->seconds;
	}



	/**
	 * @param int $hours
	 * @return static
	 */
	public function setHours($hours)
	{
		if ($hours > 23) {
			throw new InvalidArgumentException('Hours have to be less then 24.');
		}
		$this->hours = (int) $hours;

		return $this;
	}



	/**
	 * @param int $minutes
	 * @return static
	 */
	public function setMinutes($minutes)
	{
		if ($minutes > 59) {
			throw new InvalidArgumentException('Minutes have to be less then 60.');
		}
		$this->minutes = (int) $minutes;

		return $this;
	}



	/**
	 * @param float $seconds
	 * @return static
	 */
	public function setSeconds($seconds)
	{
		if ($seconds > 59) {
			throw new InvalidArgumentException('Seconds have to be less then 60.');
		}
		$this->seconds = $seconds;

		return $this;
	}



	/**
	 * @param string|int|float $modifier
	 * @return static
	 * @throws ModificationNotPossibleException
	 */
	public function modify($modifier)
	{
		$thisDateTime = $this->toInternalDateTime();
		$modified = $thisDateTime->modifyClone($modifier);

		if ($thisDateTime->format('Y-m-d') !== $modified->format('Y-m-d')) {
			throw new ModificationNotPossibleException("Modifying this by '{$modifier}' leaves a single day range.");
		}

		$this->setFromDateTime($modified);

		return $this;
	}



	/**
	 * @param string $modifier
	 * @return static
	 * @throws ModificationNotPossibleException
	 */
	public function modifyClone($modifier)
	{
		$new = clone $this;

		return $new->modify($modifier);

	}



	/**
	 * @param SingleDayTime $other
	 * @return static
	 */
	public function add(SingleDayTime $other)
	{
		$this->addOrSub($other, 1);

		return $this;
	}



	/**
	 * @param SingleDayTime $other
	 * @return static
	 */
	public function sub(SingleDayTime $other)
	{
		$this->addOrSub($other, -1);

		return $this;
	}



	/**
	 * @param $format
	 * @return string
	 */
	public function format($format)
	{
		// Todo: remove DateTime dependency OR make NOT possible to get anything of self::INTERNAL_DATE go out
		return $this->toInternalDateTime()->format($format);
	}



	/**
	 * @param \DateTime $day
	 * @return DateTime
	 */
	public function toDateTime(\DateTime $day)
	{
		$day = DateTime::from($day);
		$day->setTime($this->hours, $this->minutes, $this->seconds);

		return $day;
	}



	/**
	 * @param \DateTime|SingleDayTime|string|int|NULL $time
	 * @return SingleDayTime
	 */
	public static function from($time)
	{
		if ($time instanceof static) {
			return clone $time;
		}

		$dateTime = DateTime::from($time);

		return static::fromDateTime($dateTime);
	}



	/**
	 * @param \DateTime $dateTime
	 * @return static
	 */
	public static function fromDateTime(\DateTime $dateTime)
	{
		/** @var DateTime $dateTime */
		$dateTime = DateTime::from($dateTime);

		return new static((int) $dateTime->format('H'), (int) $dateTime->format('i'), (float) $dateTime->format('s'));
	}



	/**
	 * @inheritdoc
	 */
	public function __toString()
	{
		return $this->toInternalDateTime()->format('H:i:s');
	}



	/**
	 * @param SingleDayTime $other
	 * @param int $sign -1 (sub) or 1 (add)
	 * @throws ModificationNotPossibleException
	 */
	private function addOrSub(SingleDayTime $other, $sign)
	{
		$seconds = $this->seconds;
		$minutes = $this->minutes;
		$hours = $this->hours;

		$seconds += $sign * $other->getSeconds();
		$carryMinutes = 0;
		if ($seconds > 59 || $seconds < 0) {
			$carryMinutes = 1;
			$seconds += (-$sign) * 60;
		}

		$minutes += $sign * ($other->getMinutes() + $carryMinutes);
		$carryHours = 0;
		if ($minutes > 59 || $minutes < 0) {
			$carryHours = 1;
			$minutes += (-$sign) * 60;
		}

		$hours += $sign * ($other->getHours() + $carryHours);
		$this->validateModification($hours, $sign);

		$this->seconds = $seconds;
		$this->minutes = (int) $minutes;
		$this->hours = (int) $hours;
	}



	/**
	 * @return DateTime
	 */
	private function toInternalDateTime()
	{
		return $this->toDateTime(new DateTime(self::INTERNAL_DATE));
	}



	/**
	 * @param \DateTime $modified
	 */
	private function setFromDateTime(\DateTime $modified)
	{
		$this->setSeconds((float) $modified->format('s'));
		$this->setMinutes((int) $modified->format('i'));
		$this->setHours((int) $modified->format('H'));
	}



	/**
	 * @param int $hours
	 * @param int $sign
	 * @throws ModificationNotPossibleException
	 */
	private function validateModification($hours, $sign)
	{
		if ($hours > 23 || $hours < 0) {
			throw new ModificationNotPossibleException(
				'By ' . ($sign === 1 ? 'adding' : 'subbing') . ' this Time we would get put of one day!'
			);
		}
	}

}
