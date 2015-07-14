<?php

namespace Achse\Tests\Interval\Types;

use Achse\Math\Interval\Types\Comparison\IComparable;
use Nette;
use Tester\Assert;



trait TestComparison
{

	/**
	 * @param IComparable $five
	 * @param IComparable $six
	 */
	public function assertForComparison(IComparable $five, IComparable $six)
	{
		Assert::true($five->isLessThen($six));
		Assert::true($five->isLessThenOrEqual($six));

		Assert::false($six->isLessThen($five));
		Assert::false($six->isLessThenOrEqual($five));

		Assert::false($five->isEqual($six));
		Assert::false($six->isEqual($five));
	}

}
