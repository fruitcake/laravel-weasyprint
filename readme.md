## WeasyPrint PDF Wrapper for Laravel
[![Tests](https://github.com/fruitcake/laravel-weasyprint/workflows/Tests/badge.svg)](https://github.com/fruitcake/laravel-weasyprint/actions)
[![Packagist License](https://poser.pugx.orgfruitcake/laravel-weasyprint/license.png)](http://choosealicense.com/licenses/mit/)
[![Latest Stable Version](https://poser.pugx.org/fruitcake/laravel-weasyprint/version.png)](https://packagist.org/packages/fruitcake/laravel-weasyprint)
[![Total Downloads](https://poser.pugx.org/fruitcake/laravel-weasyprint/d/total.png)](https://packagist.org/packages/fruitcake/laravel-weasyprint)
[![Fruitcake](https://img.shields.io/badge/Powered%20By-Fruitcake-b2bc35.svg)](https://fruitcake.nl/)

This package is a ServiceProvider for WeasyPrint: [https://github.com/pontedilana/php-weasyprint](https://github.com/pontedilana/php-weasyprint).

This package is based heavily on https://github.com/barryvdh/laravel-snappy but uses WeasyPrint instead of WKHTMLTOPDF

### WeasyPrint Installation

Follow the setup here: https://doc.courtbouillon.org/weasyprint/stable/first_steps.html#installation

### Testing the WeasyPrint installation

After installing, you should be able to run WeasyPrint from the command line / shell.

```shell
weasyprint https://laravel.com/docs laravel-docs.pdf
```

### Package Installation

Require this package in your composer.json and update composer.

```bash
composer require fruitcake/laravel-weasyprint
```

### Configuration

You can publish the config file:

```bash
php artisan vendor:publish --provider="Fruitcake\WeasyPrint\ServiceProvider"
```

### Usage

You can create a new WeasyPrint instance and load an HTML string, file or view name. You can save it to a file, or inline (show in browser) or download.

Using the App container:

```php
<?php

namespace App\Http\Controllers;

use Pontedilana\PhpWeasyPrint\Pdf;

class PdfController extends Controller
{
    public function __invoke(Pdf $weasyPrint)
    {

        //To file
        $html = '<h1>Bill</h1><p>You owe me money, dude.</p>';
        $weasyPrint->generateFromHtml($html, '/tmp/bill-123.pdf');
        $weasyPrint->generate('https://laravel.com/docs/10.x', '/tmp/laravel-docs.pdf');
        
        //Or output:
        return response(
            $weasyPrint->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );
    }
}

```

Or use the Facade to access easy helper methods.

Inline a PDF:

```php
$pdf = \WeasyPrint::loadHTML('<h1>Test</h1>');
return $pdf->inline();
```

Or download:

```php
$pdf = \WeasyPrint::loadView('pdf.invoice', $data);
return $pdf->download('invoice.pdf');
```

You can chain the methods:

```php
return \WeasyPrint::loadFile('https://laravel.com/docs')->inline('laravel.pdf');
```

You can change the orientation and paper size

```php
\WeasyPrint::loadHTML($html)->setPaper('a4')->setOrientation('landscape')->setOption('margin-bottom', 0)->save('myfile.pdf')
```

If you need the output as a string, you can get the rendered PDF with the output() function, so you can save/output it yourself.

See the [php-weasyprint](https://github.com/pontedilana/php-weasyprint) for more information/settings.

### Testing - PDF fake

As an alternative to mocking, you may use the `WeasyPrint` facade's `fake` method. When using fakes, assertions are made after the code under test is executed:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use PDF;

class ExampleTest extends TestCase
{
    public function testPrintOrderShipping()
    {
        PDF::fake();
        
        // Perform order shipping...
        
        PDF::assertViewIs('view-pdf-order-shipping');
        PDF::assertSee('Name');
    }
}
```

#### Other available assertions:

```php
WeasyPrint::assertViewIs($value);
WeasyPrint::assertViewHas($key, $value = null);
WeasyPrint::assertViewHasAll(array $bindings);
WeasyPrint::assertViewMissing($key);
WeasyPrint::assertSee($value);
WeasyPrint::assertSeeText($value);
WeasyPrint::assertDontSee($value);
WeasyPrint::assertDontSeeText($value);
PDWeasyPrintF::assertFileNameIs($value);
```

### License

This WeasyPrint Wrapper for Laravel is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
