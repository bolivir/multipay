<?php

namespace Bolivir\Multipay\Tests;

use Bolivir\Multipay\Tests\Mocks\MultiPayMock;
use Exception;

/**
 * @internal
 */
class MultiPayTest extends TestCase
{
    /** @test */
    public function test_it_can_create(): void
    {
        $multiPay = $this->getMultiPayInstance();
        $multiPay->setProvider('bar');

        $this->assertSame('bar', $multiPay->provider());
    }

    /** @test */
    public function test_it_throws_an_exception_on_invalid_driver(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The given provider is invalid');

        $multiPay = $this->getMultiPayInstance();
        $multiPay->setProvider('none_existance_driver_name');
    }

    protected function getMultiPayInstance(): MultiPayMock
    {
        return new MultiPayMock();
    }
}
