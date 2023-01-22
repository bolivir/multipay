<?php

namespace Bolivir\Multipay;

use Bolivir\Multipay\Contracts\DriverInterface;
use Bolivir\Multipay\Exceptions\ConfigurationException;
use Exception;
use ReflectionClass;

class MultiPay
{
    private string $provider;

    private DriverInterface $driverInstance;

    /**
     * @param string              $provider The provider you want to use and that is configured in the config e.q. paypal, mollie
     * @param array<string,mixed> $config   If you do not want to use the config from the config file you can pass a new config here
     *
     * @throws ConfigurationException
     */
    public function setProvider(string $provider, array $config = []): self
    {
        $this->verifyProvider($provider);
        $this->provider = $provider;
        if (!empty($config)) {
            // Load the new config
        }

        return $this;
    }

    public function provider(): string
    {
        return $this->provider;
    }

    public function purchase(PaymentMeta $paymentMeta): void
    {
        $this->driverInstance->purchase($paymentMeta);
        dd($this);
    }

    /**
     * @param array<string,mixed> $body
     *
     * @throws Exception
     */
    public function capture(string $transactionId, array $body = []): void
    {
        if (method_exists($this->driverInstance, 'capture')) {
            $this->driverInstance->capture($transactionId, $body);
        } else {
            throw new Exception('This driver does not support the capture method');
        }
    }

    public function verify(string $transactionId): void
    {
        $this->driverInstance->verify($transactionId);
    }

    private function verifyProvider(string $provider): void
    {
        $providerConfig = config("multipay.providers.{$provider}");
        if (empty($providerConfig)) {
            throw new ConfigurationException('The given provider is invalid');
        }

        $this->provider = $provider;
        $providerMapClass = config("multipay.map.{$provider}");

        if (empty($providerMapClass)) {
            throw new Exception('Driver class map does not exist');
        }

        $reflectionClass = new ReflectionClass($providerMapClass);
        if (!$reflectionClass->implementsInterface(DriverInterface::class)) {
            throw new Exception('Driver must be an instance of Contracts\\DriverInterface.');
        }

        $this->driverInstance = new $providerMapClass($providerConfig);
    }
}
