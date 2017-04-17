<?php

declare(strict_types=1);

namespace Achse\Tests\Interval;

use Achse\Comparable\IComparable;
use Tester\Assert;



trait TestComparison
{

	/**
	 * @param IComparable $smaller
	 * @param IComparable $greater
	 */
	public function assertForComparison(IComparable $smaller, IComparable $greater)
	{
		Assert::true(
			$smaller->isLessThan($greater),
			sprintf('%s should be LessThan %s', $smaller, $greater)
		);
		Assert::true(
			$smaller->isLessThanOrEqual($greater),
			sprintf('%s should be LessThanOrEqual %s', $smaller, $greater)
		);
		Assert::false(
			$smaller->isGreaterThanOrEqual($greater),
			sprintf('%s should NOT be GreaterThanOrEqual %s', $smaller, $greater)
		);

		Assert::false(
			$greater->isLessThan($smaller),
			sprintf('%s should NOT be LessThan %s', $smaller, $greater)
		);
		Assert::false(
			$greater->isLessThanOrEqual($smaller),
			sprintf('%s should NOT be LessThanOrEqual %s', $smaller, $greater)
		);
		Assert::true(
			$greater->isGreaterThanOrEqual($smaller),
			sprintf('%s should be reaterThanOrEqual %s', $smaller, $greater)
		);

		Assert::true(
			$smaller->isLessThanOrEqual($smaller),
			sprintf('%s should be LessThanOrEqual %s', $smaller, $greater)
		);
		Assert::true(
			$greater->isLessThanOrEqual($greater),
			sprintf('%s should be LessThanOrEqual %s', $smaller, $greater)
		);

		Assert::true(
			$smaller->isGreaterThanOrEqual($smaller),
			sprintf('%s should be GreaterThanOrEqual %s', $smaller, $greater)
		);
		Assert::true(
			$greater->isGreaterThanOrEqual($greater),
			sprintf('%s should be GreaterThanOrEqual %s', $smaller, $greater)
		);

		Assert::true($smaller->isEqual($smaller), sprintf('%s should be Equal %s', $smaller, $greater));
		Assert::true($greater->isEqual($greater), sprintf('%s should be Equal %s', $smaller, $greater));

		Assert::false($smaller->isEqual($greater), sprintf('%s should NOT be LESS %s', $smaller, $greater));
		Assert::false($greater->isEqual($smaller), sprintf('%s should NOT be LESS %s', $smaller, $greater));
	}

}
