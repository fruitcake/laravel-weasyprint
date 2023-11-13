<?php

namespace Fruitcake\WeasyPrint\Tests;

use Fruitcake\WeasyPrint\Facades\WeasyPrint;
use Fruitcake\WeasyPrint\WeasyPrintProvider;
use Illuminate\Support\Facades\View;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__ . '/views');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return [WeasyPrintProvider::class];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return string[]
     */
    protected function getPackageAliases($app)
    {
        return [
            'WeasyPrint' => WeasyPrint::class,
        ];
    }
}
