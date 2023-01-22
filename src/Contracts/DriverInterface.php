<?php

namespace Bolivir\Multipay\Contracts;

use Bolivir\Multipay\PaymentMeta;

interface DriverInterface
{
    public function purchase(PaymentMeta $paymentMeta): mixed;

    public function verify(string $transactionId): void;
}
