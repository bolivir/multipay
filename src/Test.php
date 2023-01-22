<?php

namespace Bolivir\Multipay;

class Test
{
    public function test(): void
    {
        (new MultiPay())->setProvider('paypal')->purchase(
            (new PaymentMeta(50))
                ->addDetail('customer_number', 'test')
        );
    }

    public function capture($id, array $data = []): void
    {
        (new MultiPay())->setProvider('paypal')->capture($id, $data);
    }

    public function verify(string $id): void
    {
        (new MultiPay())->setProvider('paypal')->verify($id);
    }
}
