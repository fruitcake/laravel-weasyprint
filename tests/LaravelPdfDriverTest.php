<?php

namespace Fruitcake\WeasyPrint\Tests;

use Fruitcake\WeasyPrint\Support\WeasyPrintDriver;
use Spatie\LaravelPdf\Drivers\PdfDriver;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\PdfOptions;

class LaravelPdfDriverTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return array_merge(parent::getPackageProviders($app), [
            \Spatie\LaravelPdf\PdfServiceProvider::class,
        ]);
    }

    public function testDriverIsRegistered(): void
    {
        $driver = $this->app->make('laravel-pdf.driver.weasyprint');

        $this->assertInstanceOf(WeasyPrintDriver::class, $driver);
        $this->assertInstanceOf(PdfDriver::class, $driver);
    }

    public function testGeneratePdfFromHtml(): void
    {
        $driver = $this->app->make('laravel-pdf.driver.weasyprint');

        $options = new PdfOptions();
        $content = $driver->generatePdf('<h1>Test</h1>', null, null, $options);

        $this->assertNotEmpty($content);
        $this->assertStringStartsWith('%PDF', $content);
    }

    public function testGeneratePdfWithFormatAndOrientation(): void
    {
        $driver = $this->app->make('laravel-pdf.driver.weasyprint');

        $options = new PdfOptions();
        $options->format = 'a4';
        $options->orientation = 'landscape';

        $content = $driver->generatePdf('<h1>Test</h1>', null, null, $options);

        $this->assertNotEmpty($content);
        $this->assertStringStartsWith('%PDF', $content);
    }

    public function testGeneratePdfWithMargins(): void
    {
        $driver = $this->app->make('laravel-pdf.driver.weasyprint');

        $options = new PdfOptions();
        $options->margins = [
            'top' => 10,
            'right' => 10,
            'bottom' => 10,
            'left' => 10,
            'unit' => 'mm',
        ];

        $content = $driver->generatePdf('<h1>Test</h1>', null, null, $options);

        $this->assertNotEmpty($content);
        $this->assertStringStartsWith('%PDF', $content);
    }

    public function testGeneratePdfWithCustomPaperSize(): void
    {
        $driver = $this->app->make('laravel-pdf.driver.weasyprint');

        $options = new PdfOptions();
        $options->paperSize = [
            'width' => 200,
            'height' => 300,
            'unit' => 'mm',
        ];

        $content = $driver->generatePdf('<h1>Test</h1>', null, null, $options);

        $this->assertNotEmpty($content);
        $this->assertStringStartsWith('%PDF', $content);
    }

    public function testGeneratePdfWithHeaderAndFooter(): void
    {
        $driver = $this->app->make('laravel-pdf.driver.weasyprint');

        $options = new PdfOptions();
        $content = $driver->generatePdf(
            '<html><body><p>Body</p></body></html>',
            '<span>Header</span>',
            '<span>Footer</span>',
            $options,
        );

        $this->assertNotEmpty($content);
        $this->assertStringStartsWith('%PDF', $content);
    }

    public function testSavePdf(): void
    {
        $driver = $this->app->make('laravel-pdf.driver.weasyprint');

        $options = new PdfOptions();
        $path = tempnam(sys_get_temp_dir(), 'pdf_') . '.pdf';

        try {
            $driver->savePdf('<h1>Test</h1>', null, null, $options, $path);

            $this->assertFileExists($path);
            $this->assertStringStartsWith('%PDF', file_get_contents($path));
        } finally {
            @unlink($path);
        }
    }

    public function testViaSpatieDriverMethod(): void
    {
        $builder = Pdf::driver('weasyprint')->html('<h1>Test</h1>');

        $content = $builder->base64();

        $this->assertNotEmpty($content);
        $decoded = base64_decode($content);
        $this->assertStringStartsWith('%PDF', $decoded);
    }

    public function testViaSpatieWithView(): void
    {
        $builder = Pdf::driver('weasyprint')->view('test');

        $content = $builder->base64();

        $this->assertNotEmpty($content);
        $decoded = base64_decode($content);
        $this->assertStringStartsWith('%PDF', $decoded);
    }
}
