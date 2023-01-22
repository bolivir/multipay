<?php

namespace Bolivir\Multipay\Drivers\Paypal;

use Bolivir\Multipay\Abstracts\Driver;
use Bolivir\Multipay\Contracts\ManualCaptureInterface;
use Bolivir\Multipay\Exceptions\TransactionNotApprovedException;
use Bolivir\Multipay\Exceptions\TransactionNotFoundException;
use Bolivir\Multipay\PaymentMeta;
use Exception;

class Paypal extends Driver implements ManualCaptureInterface
{
    private const SANDBOX_API_BASE_URL = 'https://api-m.sandbox.paypal.com';

    private const LIVE_API_BASE_URL = 'https://api-m.paypal.com';

    private const PAYMENT_ENDPOINT_URL = '/v1/payments/payment';

    private const AUTH_TOKEN_ENDPOINT_URL = '/v1/oauth2/token';

    private const CAPTURE_ENDPOINT_URL = '/v1/payments/payment/{transactionID}/execute';

    private const VERIFY_ENDPOINT_URL = '/v1/payments/payment/{transactionID}';

    private mixed $config;

    /** @param array<string, mixed> $config */
    public function __construct(array $config)
    {
        $this->config = (object) $config;
    }

    /**
     * Capturing will transfer the funds from the client account to your account.
     *
     * @param array<string,string> $body
     *
     * @throws Exception
     */
    public function capture(string $transactionId, array $body = []): void
    {
        if (!array_key_exists('payer_id', $body) || empty($body['payer_id'])) {
            throw new Exception('To capture a paypal transaction, the payer_id is required as body parameter');
        }

        $accessToken = $this->getAccessToken();
        $url = str_replace('{transactionID}', $transactionId, $this->endpointUrl().self::CAPTURE_ENDPOINT_URL);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer '.$accessToken,
            ],
        ]);

        curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if (200 !== $statusCode) {
            throw new Exception("Capturing funds failed with status code: {$statusCode}");
        }
    }

    /**
     * @throws TransactionNotFoundException
     * @throws TransactionNotApprovedException
     */
    public function verify(string $transactionId): void
    {
        $accessToken = $this->getAccessToken();
        $url = str_replace('{transactionID}', $transactionId, $this->endpointUrl().self::VERIFY_ENDPOINT_URL);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer '.$accessToken,
            ],
        ]);

        $response = curl_exec($curl);
        if (!is_bool($response)) {
            $response = json_decode($response, true);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (404 === $statusCode) {
                throw new TransactionNotFoundException();
            }
            if ('approved' !== $response['state']) {
                throw new TransactionNotApprovedException();
            }
        }

        throw new TransactionNotApprovedException();
    }

    /**
     * @return array<mixed>
     *
     * @throws Exception
     */
    public function purchase(PaymentMeta $paymentMeta): array
    {
        $accessToken = $this->getAccessToken();
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->endpointUrl().self::PAYMENT_ENDPOINT_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'intent' => 'sale',
                'redirect_urls' => [
                    'return_url' => $this->config->return_url,
                    'cancel_url' => $this->config->cancel_url,
                ],
                'payer' => [
                    'payment_method' => 'paypal',
                ],
                'transactions' => [
                    [
                        'amount' => [
                            'total' => $paymentMeta->amount(),
                            'currency' => $this->config->currency,
                        ],
                        'additional_data' => $paymentMeta->getDetails(),
                    ],
                ],
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer '.$accessToken,
            ],
        ]);

        $response = curl_exec($curl);
        if (is_bool($response)) {
            throw new Exception('Curl request failed');
        }
        curl_close($curl);

        return json_decode($response, true);
    }

    private function endpointUrl(): string
    {
        if (empty($this->config->mode) || 'sandbox' === $this->config->mode) {
            return self::SANDBOX_API_BASE_URL;
        }

        return self::LIVE_API_BASE_URL;
    }

    private function getAccessToken(): string
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->endpointUrl().self::AUTH_TOKEN_ENDPOINT_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD => $this->config->client_id.':'.$this->config->secret,
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Accept-Language: en_US',
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($curl);
        if (is_bool($response)) {
            throw new Exception('Get access token request failed');
        }

        curl_close($curl);
        $response = json_decode($response, true);
        if (!isset($response['access_token'])) {
            throw new Exception('Access token request failed, verify client_id and secret are correct');
        }

        return $response['access_token'];
    }
}
