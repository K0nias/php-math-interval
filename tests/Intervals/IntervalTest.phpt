<?php

/**
 * @testCase
 */

namespace Achse\Tests\Interval\Types;

require __DIR__ . '/../bootstrap.php';

use Achse\Math\Interval\Intervals\IntegerInterval;
use Achse\Math\Interval\Types\Integer;
use Achse\Math\Interval\Utils\IntegerIntervalStringParser;
use Tester\Assert;
use Tester\TestCase;



class IntervalTest extends TestCase
{

	/**
	 * @var IntegerInterval
	 */
	private $intervalOneFourClosed;

	/**
	 * @var IntegerInterval
	 */
	private $intervalOneFourOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalTwoThreeClosed;

	/**
	 * @var IntegerInterval
	 */
	private $intervalTwoThreeOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalOneTwoClosed;

	/**
	 * @var IntegerInterval
	 */
	private $intervalOneThreeOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalTwoFourOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalOneTwoOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalThreeFourOpened;

	/**
	 * @var IntegerInterval
	 */
	private $intervalThreeFourClosed;

	/**
	 * @var IntegerInterval
	 */
	private $intervalTwoFourClosed;

	/**
	 * @var IntegerInterval
	 */
	private $intervalZeroToFiveClosed;



	protected function setUp()
	{
		parent::setUp();

		$this->intervalOneFourClosed = IntegerIntervalStringParser::parse('[1, 4]');
		$this->intervalOneFourOpened = IntegerIntervalStringParser::parse('(1, 4)');

		$this->intervalTwoThreeClosed = IntegerIntervalStringParser::parse('[2, 3]');
		$this->intervalTwoThreeOpened = IntegerIntervalStringParser::parse('(2, 3)');

		$this->intervalOneTwoClosed = IntegerIntervalStringParser::parse('[1, 2]');
		$this->intervalOneTwoOpened = IntegerIntervalStringParser::parse('(1, 2)');

		$this->intervalTwoThreeClosed = IntegerIntervalStringParser::parse('[2, 3]');
		$this->intervalTwoThreeOpened = IntegerIntervalStringParser::parse('(2, 3)');

		$this->intervalThreeFourClosed = IntegerIntervalStringParser::parse('[3, 4]');
		$this->intervalThreeFourOpened = IntegerIntervalStringParser::parse('(3, 4)');

		$this->intervalTwoFourClosed = IntegerIntervalStringParser::parse('[2, 4]');
		$this->intervalTwoFourOpened = IntegerIntervalStringParser::parse('(2, 4)');

		$this->intervalOneThreeOpened = IntegerIntervalStringParser::parse('(1, 3)');

		$this->intervalZeroToFiveClosed = IntegerIntervalStringParser::parse('[0, 5]');
	}



	public function testIsContainingElement()
	{
		$interval = IntegerIntervalStringParser::parse('[1, 2]');
		Assert::true($interval->isContainingElement(new Integer(1)));
		Assert::true($interval->isContainingElement(new Integer(2)));

		$interval = IntegerIntervalStringParser::parse('(1, 2)');
		Assert::false($interval->isContainingElement(new Integer(1)));
		Assert::false($interval->isContainingElement(new Integer(2)));

		$interval = IntegerIntervalStringParser::parse('[1, 3)');
		Assert::true($interval->isContainingElement(new Integer(1)));
		Assert::true($interval->isContainingElement(new Integer(2)));
		Assert::false($interval->isContainingElement(new Integer(3)));

		$interval = IntegerIntervalStringParser::parse('(1, 3]');
		Assert::false($interval->isContainingElement(new Integer(1)));
		Assert::true($interval->isContainingElement(new Integer(2)));
		Assert::true($interval->isContainingElement(new Integer(3)));
	}



	public function testIsContaining()
	{

		// [1, 4] contains (1, 4)
		Assert::true($this->intervalOneFourClosed->isContaining($this->intervalOneFourOpened));
		// (1, 4) NOT contains [1, 4]
		Assert::false($this->intervalOneFourOpened->isContaining($this->intervalOneFourClosed));

		// [1, 4] contains [2, 3]
		Assert::true($this->intervalOneFourClosed->isContaining($this->intervalTwoThreeClosed));
		// [2, 3] NOT contains [1, 4]
		Assert::false($this->intervalTwoThreeClosed->isContaining($this->intervalOneFourClosed));

		// [1, 4] contains (2, 3)
		Assert::true($this->intervalOneFourClosed->isContaining($this->intervalTwoThreeOpened));
		// (2, 3) NOT contains [1, 4]
		Assert::false($this->intervalTwoThreeOpened->isContaining($this->intervalOneFourClosed));


		// (1, 4) NOT contains [3, 4]
		Assert::false($this->intervalOneFourOpened->isContaining($this->intervalThreeFourClosed));
		// (1, 4) NOT contains [1, 2]
		Assert::false($this->intervalOneFourOpened->isContaining($this->intervalOneTwoClosed));

		$left = IntegerIntervalStringParser::parse('[1, 3]');
		$right = IntegerIntervalStringParser::parse('[3, 4]');

		Assert::false($left->isContaining($right));
		Assert::false($right->isContaining($left));
	}



	public function testIsOverlappedFromRightBy()
	{
		Assert::true($this->intervalOneTwoClosed->isOverlappedFromRightBy($this->intervalTwoThreeClosed));
		Assert::false($this->intervalTwoThreeClosed->isOverlappedFromRightBy($this->intervalOneTwoClosed));

		// (1, 2) ~ [2, 3]
		Assert::false($this->intervalOneTwoOpened->isOverlappedFromRightBy($this->intervalTwoThreeClosed));
		// [1, 2] ~ (2, 3)
		Assert::false($this->intervalOneTwoClosed->isOverlappedFromRightBy($this->intervalTwoThreeOpened));

		Assert::true($this->intervalOneThreeOpened->isOverlappedFromRightBy($this->intervalTwoFourOpened));
	}



	public function testIsColliding()
	{
		Assert::true($this->intervalOneTwoClosed->isColliding($this->intervalTwoThreeClosed));
		Assert::true($this->intervalTwoThreeClosed->isColliding($this->intervalOneTwoClosed));

		Assert::false($this->intervalOneTwoClosed->isColliding($this->intervalTwoThreeOpened));
		Assert::false($this->intervalOneTwoOpened->isColliding($this->intervalTwoThreeClosed));

		Assert::true($this->intervalOneThreeOpened->isColliding($this->intervalTwoFourOpened));
		Assert::true($this->intervalTwoFourOpened->isColliding($this->intervalOneThreeOpened));
	}



	public function testGetIntersection()
	{
		$intervalTwoTwoClosed = IntegerIntervalStringParser::parse('[2, 2]');

		$intersection = $this->intervalOneTwoClosed->getIntersection($this->intervalTwoThreeClosed);
		$this->assertInterval($intervalTwoTwoClosed, $intersection);

		$intersection = $this->intervalTwoThreeClosed->getIntersection($this->intervalOneTwoClosed);
		$this->assertInterval($intervalTwoTwoClosed, $intersection);

		Assert::null($this->intervalOneTwoClosed->getIntersection($this->intervalTwoThreeOpened));
		Assert::null($this->intervalOneTwoOpened->getIntersection($this->intervalTwoThreeClosed));

		$intervalTwoThreeOpened = IntegerIntervalStringParser::parse('(2, 3)');

		// (1, 3) ∩ (2, 4) ⟺ (2, 3)
		$intersection = $this->intervalOneThreeOpened->getIntersection($this->intervalTwoFourOpened);
		$this->assertInterval($intervalTwoThreeOpened, $intersection);

		$intersection = $this->intervalTwoFourOpened->getIntersection($this->intervalOneThreeOpened);
		$this->assertInterval($intervalTwoThreeOpened, $intersection);
	}



	public function testGetDifference()
	{
		// [1, 4] \ [0, 5]
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalZeroToFiveClosed);
		Assert::count(0, $diff);

		// [1, 4] \ [1, 4]
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalOneFourClosed);
		Assert::count(0, $diff);

		// (1, 4) \ (1, 4)
		$diff = $this->intervalOneFourOpened->getDifference($this->intervalOneFourOpened);
		Assert::count(0, $diff);

		// [1, 4] \ [2, 4]
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalTwoFourClosed);
		Assert::count(1, $diff);
		Assert::equal('[1, 2)', (string) reset($diff));

		// [1, 4] \ [1, 2]
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalOneTwoClosed);
		Assert::count(1, $diff);
		Assert::equal('(2, 4]', (string) reset($diff));

		// [1, 4] \ (2, 4)
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalTwoFourOpened);
		Assert::count(2, $diff);
		Assert::equal('[1, 2]', (string) $diff[0]);
		Assert::equal('[4, 4]', (string) $diff[1]);

		// [1, 4] \ (1, 2)
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalOneTwoOpened);
		Assert::count(2, $diff);
		Assert::equal('[1, 1]', (string) $diff[0]);
		Assert::equal('[2, 4]', (string) $diff[1]);

		// [1, 4] \ [2, 3]
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalTwoThreeClosed);
		Assert::count(2, $diff);
		Assert::equal('[1, 2)', (string) $diff[0]);
		Assert::equal('(3, 4]', (string) $diff[1]);

		// [1, 4] \ (2, 3)
		$diff = $this->intervalOneFourClosed->getDifference($this->intervalTwoThreeOpened);
		Assert::count(2, $diff);
		Assert::equal('[1, 2]', (string) $diff[0]);
		Assert::equal('[3, 4]', (string) $diff[1]);
	}



	/**
	 * @param IntegerInterval $expected
	 * @param IntegerInterval $actual
	 */
	private function assertInterval(IntegerInterval $expected, IntegerInterval $actual)
	{
		Assert::equal((string) $expected, (string) $actual);
	}

}



(new IntervalTest())->run();
