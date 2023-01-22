<?php

namespace Bolivir\Multipay\Contracts;

interface ManualCaptureInterface
{
    /** @param array<string, mixed> $body */
    public function capture(string $transactionId, array $body = []): void;
}
