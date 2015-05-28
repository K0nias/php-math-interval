<?php

namespace Achse\Interval\Types;

use Achse\Interval\Types\Comparison\ComparisonMethods;
use Achse\Interval\Types\Comparison\IComparable;
use Nette\InvalidArgumentException;
use Nette\NotImplementedException;
use Nette\Object;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;



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
			throw new \LogicException('You cannot compare sheep with the goat.');
		}

		return gmp_cmp($this->toSeconds(), $other->toSeconds());
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
		$this->hours = $hours;

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
		$this->minutes = $minutes;

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
	 */
	public function modify($modifier)
	{
		if (is_numeric($modifier)) {
			$this->add(new static(0, 0, $modifier));
		}

		throw new NotImplementedException();
//		$result = Strings::match();

		return $this;
	}



	/**
	 * @param string $modifier
	 * @return static
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
		$seconds = $this->seconds;
		$minutes = $this->minutes;
		$hours = $this->hours;

		$seconds += $other->getSeconds();
		$carryMinutes = 0;
		if ($seconds > 59) {
			$carryMinutes = (int)($seconds / 60);
			$seconds = $seconds % 60;
		}

		$minutes += ($other->getMinutes() + $carryMinutes);
		$carryHours = 0;
		if ($minutes > 59) {
			$carryHours = (int)($minutes / 60);
			$minutes = $minutes % 60;
		}

		$hours += ($other->getHours() + $carryHours);
		if ($hours > 23) {
			throw new InvalidArgumentException('By adding this Time we would overcome midnight!');
		}

		$this->seconds = $seconds;
		$this->minutes = $minutes;
		$this->hours = $hours;

		return $this;
	}



	/**
	 * @param SingleDayTime $other
	 * @return static
	 */
	public function sub(SingleDayTime $other)
	{
		$seconds = $this->seconds;
		$minutes = $this->minutes;
		$hours = $this->hours;

		$this->seconds = $seconds;
		$this->minutes = $minutes;
		$this->hours = $hours;

		return $this;
	}



	/**
	 * @param $format
	 * @return string
	 */
	public function format($format)
	{
		// Todo: remove DateTime dependency OR make NOT possible to get anything of self::INTERNAL_DATE go out
		$dateTime = new DateTime(self::INTERNAL_DATE . " {$this->hours}:{$this->minutes}:{$this->seconds}");

		return $dateTime->format($format);
	}

}
