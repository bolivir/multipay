<?php

namespace Bolivir\Multipay\Tests\Drivers;

use Bolivir\Multipay\Abstracts\Driver;
use Bolivir\Multipay\PaymentMeta;

class FooBarDriver extends Driver
{
    public function purchase(PaymentMeta $paymentMeta): mixed
    {
        return null;
    }

    public function verify(string $transactionId): void
    {
        // TODO: Implement verify() method.
    }
}
