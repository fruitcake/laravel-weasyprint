<?php

namespace Fruitcake\WeasyPrint;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Pontedilana\PhpWeasyPrint\Pdf;

class WeasyPrintProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/weasyprint.php';
        $this->mergeConfigFrom($configPath, 'weasyprint');
    }

    public function boot()
    {
        $configPath = __DIR__ . '/../config/weasyprint.php';
        $this->publishes([$configPath => config_path('weasyprint.php')], 'config');

        $this->app->bind('weasyprint.pdf', function ($app) {
            $binary = $app['config']->get('weasyprint.pdf.binary', '/usr/local/bin/weasyprint');
            $options = $app['config']->get('weasyprint.pdf.options', array());
            $env = $app['config']->get('weasyprint.pdf.env', array());
            $timeout = $app['config']->get('weasyprint.pdf.timeout', false);

            $weasy = new IlluminateWeasyPrintPdf($app['files'], $binary, $options, $env);
            if ($timeout && is_int($timeout)) {
                $weasy->setTimeout($timeout);
            }

            if (null === $timeout) {
            	$weasy->disableTimeout();
            }
            
            return $weasy;
        });
        $this->app->alias('weasyprint.pdf', Pdf::class);

        $this->app->bind('weasyprint.pdf.wrapper', function ($app) {
            $pageSize = $app['config']->get('weasyprint.pdf.page.size');
            $pageOrientation = $app['config']->get('weasyprint.pdf.page.orientation');

            $wrapper = new WeasyPrintWrapper($app['weasyprint.pdf']);

            if ($pageSize) {
                $wrapper->setPaper($pageSize);
            }
            if ($pageOrientation) {
                $wrapper->setOrientation($pageOrientation);
            }

            return $wrapper;
        });
        $this->app->alias('weasyprint.pdf.wrapper', WeasyPrintWrapper::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('weasyprint.pdf', 'weasyprint.pdf.wrapper', WeasyPrintWrapper::class, Pdf::class);
    }
}
