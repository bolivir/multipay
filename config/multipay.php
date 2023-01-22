<?php

return [
    'default_provider' => 'paypal',
    'providers' => [
        'paypal' => [
            'client_id' => '',
            'secret' => '',
            'mode' => 'sandbox',
            'currency' => 'EUR',
            'return_url' => '',
            'cancel_url' => '',
        ],
        'mollie' => [
            'api_key' => '',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Maps
    |--------------------------------------------------------------------------
    |
    | This is the array of Classes that maps to Drivers above.
    | You can create your own driver if you like and add the
    | config in the drivers array and the class to use for
    | here with the same name. You will have to extend
    |  Bolivir\Multipay\Abstracts\Driver in your driver.
    |
    */
    'map' => [
        'paypal' => \Bolivir\Multipay\Drivers\Paypal\Paypal::class,
    ],
];
