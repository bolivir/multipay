<?php

namespace Bolivir\Multipay\Tests;

use Bolivir\Multipay\PaymentMeta;

/**
 * @internal
 */
class PaymentMetaTest extends TestCase
{
    /** @test */
    public function test_it_can_create_and_sets_uuid(): void
    {
        $paymentMeta = new PaymentMeta(0);
        $this->assertNotEmpty($paymentMeta->uuid());
        $this->assertNull($paymentMeta->transactionId());
    }

    /** @test */
    public function test_it_does_accept_an_custom_uuid(): void
    {
        $paymentMeta = new PaymentMeta(0, 'test-uuid');
        $this->assertSame($paymentMeta->uuid(), 'test-uuid');
    }

    /** @test */
    public function test_it_can_change(): void
    {
        $paymentMeta = new PaymentMeta(0);
        $paymentMeta->changeAmount(10);
        $paymentMeta->changeTransactionId('transaction-id');

        $this->assertSame($paymentMeta->amount(), 10);
        $this->assertSame($paymentMeta->transactionId(), 'transaction-id');
    }

    /** @test */
    public function test_it_can_add_and_get_detail(): void
    {
        $paymentMeta = new PaymentMeta(0);
        $paymentMeta->addDetail('customer-id', 'the-customer-id');
        $paymentMeta->addDetail('date', '01-01-1970');

        $this->assertSame('the-customer-id', $paymentMeta->getDetail('customer-id'));
        $this->assertSame('01-01-1970', $paymentMeta->getDetail('date'));
        $this->assertNull($paymentMeta->getDetail('none-existing-key'));
    }
}
