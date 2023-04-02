<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Tests;

use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase as BaseTestCase;

#[Group('unit'), Group('packed-array')]
abstract class TestCase extends BaseTestCase
{
    protected bool $assertionsIsEnabled = true;

    #[Before]
    public function readAssertions(): void
    {
        $this->assertionsIsEnabled = (bool)\assert_options(\ASSERT_ACTIVE);
    }

    #[After]
    public function rollbackAssertions(): void
    {
        \assert_options(\ASSERT_ACTIVE, $this->assertionsIsEnabled);
    }

    protected function skipIfAssertionDisabled(): void
    {
        if ((int)ini_get('zend.assertions') !== 1) {
            $this->markTestSkipped('zend.assertions=1 php.ini option is required to perform this test');
        }

        \assert_options(\ASSERT_ACTIVE, true);
    }
}
