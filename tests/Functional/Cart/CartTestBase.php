<?php

namespace App\Tests\Functional\Cart;

use App\Tests\Functional\TestBase;

class CartTestBase extends TestBase
{
    protected string $endpoint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->endpoint = '/cart';
    }
}
