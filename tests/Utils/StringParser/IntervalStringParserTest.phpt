<?php

/**
 * @testCase
 */

declare(strict_types = 1);

namespace Achse\Tests\Interval\Utils\StringParser;

require_once __DIR__ . '/../../bootstrap.php';

use Achse\Math\Interval\IntervalParseErrorException;
use Achse\Math\Interval\Utils\StringParser\IntegerIntervalStringParser;
use Achse\Math\Interval\Utils\StringParser\IntervalStringParser;
use LogicException;
use Tester\Assert;
use Tester\TestCase;



class IntervalStringParserTest extends TestCase
{

	public function testStaticAbstractError()
	{
		Assert::exception(
			function () {
				IntervalStringParser::parse('Whatever!');
			},
			LogicException::class
		);
	}



	/**
	 * @dataProvider getDataForParsingErrorTest
	 *
	 * @param string $string
	 * @param string $exceptionText
	 */
	public function testParsingErrors(string $string, string $exceptionText = ' -- Definitely not! --')
	{
		Assert::exception(
			function () use ($string, $exceptionText) {
				IntegerIntervalStringParser::parse($string);
			},
			IntervalParseErrorException::class,
			$exceptionText
		);
	}



	/**
	 * @return string[]
	 */
	public function getDataForParsingErrorTest() : array
	{
		return [
			['Whatever!', 'Unexpected number of boundaries. Check if given string contains only one delimiter (,).'],
			['[]', 'Unexpected number of boundaries. Check if given string contains only one delimiter (,).'],
			['[,]', "Boundary part '[' is too short. It must be at leas 2 character long. Example: '(1' or '9]'."],
		];
	}

}



(new IntervalStringParserTest())->run();
