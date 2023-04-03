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
        if ((int)ini_get('zend.assertions') === 1) {
            \assert_options(\ASSERT_ACTIVE, true);

            return;
        }

        $this->markTestIncomplete('zend.assertions=1 or zend.assertions=0 php.ini option is required to perform this test');
    }
}
