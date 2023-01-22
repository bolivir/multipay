<?php

namespace Bolivir\Multipay\Traits;

trait HasDetails
{
    /** @var array<string,mixed> */
    protected array $details = [];

    public function addDetail(string $key, string $value): self
    {
        $this->details[$key] = $value;

        return $this;
    }

    public function getDetail(string $key): ?string
    {
        return $this->details[$key] ?? null;
    }

    /** @return array<string,string> */
    public function getDetails(): array
    {
        return $this->details;
    }
}
