<?php

namespace Salehye\Seo\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Salehye\Seo\Facades\Seo;
use Salehye\Seo\Providers\SeoServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            SeoServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Seo' => Seo::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default config
        $app['config']->set('seo.site_name', 'Test Site');
        $app['config']->set('seo.twitter_handle', '@test');
    }
}
