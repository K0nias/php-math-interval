<?php

declare(strict_types=1);

namespace Achse\Math\Interval\Integer;

use Achse\Comparable\ComparisonMethods;
use Achse\Comparable\IComparable;
use Achse\Math\Interval\Utils;
use InvalidArgumentException;



final class Integer implements IComparable
{

	use ComparisonMethods;

	/**
	 * @var int
	 */
	private $internal;



	/**
	 * @param int $internal
	 */
	public function __construct(int $internal)
	{
		$this->internal = $internal;
	}



	/**
	 * @param string $string
	 * @return static
	 */
	public static function fromString(string $string): Integer
	{
		if (!is_numeric($string) || (string) (int) $string !== $string) {
			throw new InvalidArgumentException(sprintf('\'%s\' in not integer-like.', $string));
		}

		return new static((int) $string);
	}



	/**
	 * @inheritdoc
	 */
	public function compare(IComparable $other): int
	{
		/** @var static $other */
		Utils::validateClassType(static::class, $other);

		return Utils::numberCmp($this->internal, $other->toInt());
	}



	/**
	 * @return int
	 */
	public function toInt(): int
	{
		return $this->internal;
	}



	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return (string) $this->toInt();
	}
}
