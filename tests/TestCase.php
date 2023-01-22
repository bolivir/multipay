<?php

namespace Bolivir\Multipay\Tests;

use Bolivir\Multipay\Tests\Drivers\FooBarDriver;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * @internal
 */
class TestCase extends BaseTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $settings = require __DIR__.'/../config/multipay.php';
        $settings['providers']['bar'] = ['key' => 'foo'];
        $settings['map']['bar'] = FooBarDriver::class;

        $app['config']->set('multipay', $settings);
    }
}
