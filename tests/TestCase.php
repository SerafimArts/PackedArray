<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Tests;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase as BaseTestCase;

#[Group('unit'), Group('packed-array')]
abstract class TestCase extends BaseTestCase
{
}
