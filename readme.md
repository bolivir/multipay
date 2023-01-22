[![Software License][ico-license]](LICENSE.md)

# MultiPay  
A Laravel package for multiple Payment Gateway Integrations.

## List of available drivers 
[✔] Paypal

## Installation
This package can be installed via composer:
```console
$ composer require bolivir/multipay
```
## Configuration
This package uses a config file `multipay.php`, after installation make sure to run the following artisan command
```console
$ php artisan vendor:publish --provider="Bolivir\Multipay\PaymentServiceProvider" --tag="config"
```
In the configuration file you can setup the payment providers you would like to use. In this example you see the configuration for paypal.
Make sure to fill in all fields to be able to use the payment provider.
```php
'providers' => [
        'paypal' => [
            'client_id' => '',
            'secret' => '',
            'mode' => 'sandbox',
            'currency' => 'EUR',
            'return_url' => '',
            'cancel_url' => '',
        ],
        ...other available providers
    ],
```

## How to use
If you have configured the desired payment provider you want to use you can use multipay like below:


#### Perform a purchase action
```php
// Create the meta for the payment
$paymentMeta = new \Bolivir\Multipay\PaymentMeta();
$paymentMeta->addDetail('customer_number', 'foo');

// Create the multi pay instance and set the provider
$multiPay = new \Bolivir\Multipay\MultiPay();
$multiPay->setProvider('paypal');

// Make the purchase
$multiPay->purchase($paymentMeta);
```

#### Perform a capture action
Some providers do not directly capture the funds and instead it will approve the purchase request, but you need to manually capture the funds.
This can be done by calling the `capture` function. The following providers needs manual capture:
```php
$transactionId = 'xxx' // The transaction id you got when calling purchase
$body = [
    // Paypal example body, this id you will get when they redirect back to the callback url
    'payer_id' => 'xxxxx',
]
// Create the multi pay instance and set the provider
$multiPay = new \Bolivir\Multipay\MultiPay();
$multiPay->setProvider('paypal');
$multiPay->capture($transactionId, $body);
```

#### Perform a verify action
You can verify if a purchase was succesfully by calling the `verify` method
```php
$transactionId = 'xxx' // The transaction ID you get when the provider calls your callback URL.
// Create the multi pay instance and set the provider
$multiPay = new \Bolivir\Multipay\MultiPay();
$multiPay->setProvider('paypal');
$multiPay->verify($transactionId);
```

# Create custom drivers
TODO

# Change log
Please see CHANGELOG for more information on what has been changed recently.

# Security
If you discover any security related issues, please email mosselmanricardo@gmail.com instead of using the issue tracker.

# Credits
- [Bolivir](https://github.com/bolivir)
- [All Contributors](https://github.com/bolivir/multipay/graphs/contributors)
