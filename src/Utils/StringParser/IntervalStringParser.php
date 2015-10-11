<?php

namespace Achse\Math\Interval\Utils\StringParser;

use Achse\Math\Interval\Boundaries\Boundary;
use Achse\Math\Interval\IntervalParseErrorException;
use Achse\Math\Interval\Intervals\Interval;
use Nette\NotImplementedException;
use Nette\Object;
use Nette\Utils\Strings;



abstract class IntervalStringParser extends Object
{

	private static $parenthesisToStateTranslationTable = [
		Boundary::STRING_OPENED_LEFT => Boundary::OPENED,
		Boundary::STRING_OPENED_RIGHT => Boundary::OPENED,
		Boundary::STRING_CLOSED_LEFT => Boundary::CLOSED,
		Boundary::STRING_CLOSED_RIGHT => Boundary::CLOSED,
	];



	/**
	 * @param string $string
	 * @return Interval
	 */
	public static function parse($string)
	{
		throw new NotImplementedException('Not implemented in this abstract class. Implement this in child.');
	}



	/**
	 * @param string $string
	 * @return string[]
	 * @throws IntervalParseErrorException
	 */
	protected static function parseBoundariesStringsFromString($string)
	{
		$boundaries = explode(',', $string);

		if (count($boundaries) != 2) {
			throw new IntervalParseErrorException(
				'Unexpected number of boundaries. Check if given string contains only one delimiter '
				. '(' . Interval::STRING_DELIMITER . ').'
			);
		}

		return array_map('trim', $boundaries);
	}



	/**
	 * @param string $string
	 * @return Boundary
	 * @throws IntervalParseErrorException
	 */
	protected static function parseBoundaryDataFromString($string)
	{
		$letters = Strings::split($string, '//u', PREG_SPLIT_NO_EMPTY);

		if (count($letters) < 2) {
			throw new IntervalParseErrorException(
				"Boundary part '{$string}' is too short. It must be at leas 2 character long. Example: '"
				. Boundary::STRING_OPENED_LEFT . "1' or '9" . Boundary::STRING_CLOSED_RIGHT . "'."
			);
		}

		return self::getElementAndStateData($letters);
	}



	/**
	 * @param int $character
	 * @return bool
	 */
	protected static function isCharacterBoundaryType($character)
	{
		return isset(self::$parenthesisToStateTranslationTable[$character]);
	}



	/**
	 * @param int $character
	 * @return bool
	 */
	protected static function getTypeByCharacter($character)
	{
		return self::$parenthesisToStateTranslationTable[$character];
	}



	/**
	 * @param int[] $letters
	 * @return array
	 * @throws IntervalParseErrorException
	 */
	protected static function getElementAndStateData($letters)
	{
		$firstCharacter = reset($letters);
		$lastCharacter = end($letters);

		if (self::isCharacterBoundaryType($firstCharacter)) {
			array_shift($letters);
			$state = self::getTypeByCharacter($firstCharacter);

		} elseif (self::isCharacterBoundaryType($lastCharacter)) {
			array_pop($letters);
			$state = self::getTypeByCharacter($lastCharacter);

		} else {
			throw new IntervalParseErrorException('Boundary type character not found.');
		}

		return [implode('', $letters), $state];
	}



	/**
	 * @param string $string
	 * @return Boundary
	 */
	protected static function parseBoundary($string)
	{
		throw new NotImplementedException('Not implemented in this abstract class. Implement this in child.');
	}

}
