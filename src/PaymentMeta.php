<?php

namespace Bolivir\Multipay;

use Bolivir\Multipay\Traits\HasDetails;
use Ramsey\Uuid\Uuid;

class PaymentMeta
{
    use HasDetails;

    protected string $driver;

    protected ?string $transactionId = null;

    private string $uuid;

    private int|float $amount;

    public function __construct(int|float $amount, ?string $uuid = null)
    {
        $this->changeAmount($amount);
        $this->uuid = $this->changeUuid($uuid);
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function changeAmount(int|float $amount): void
    {
        $this->amount = $amount;
    }

    public function amount(): int|float
    {
        return $this->amount;
    }

    public function changeTransactionId(string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function transactionId(): ?string
    {
        return $this->transactionId;
    }

    private function changeUuid(?string $uuid): string
    {
        if (empty($uuid)) {
            $uuid = Uuid::uuid4()->toString();
        }

        return $uuid;
    }
}
