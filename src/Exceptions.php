<?php

declare(strict_types=1);

namespace Achse\Math\Interval;

use InvalidArgumentException;



class ModificationNotPossibleException extends \Exception
{

}



class IntervalParseErrorException extends \Exception
{

}



class IntervalRangesInvalidException extends InvalidArgumentException
{

}



class InvalidBoundaryTypeException extends InvalidArgumentException
{

}
