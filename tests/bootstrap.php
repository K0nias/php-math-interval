<?php

namespace Achse\Interval\Test;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Types/TestComparison.php';

if (!class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

