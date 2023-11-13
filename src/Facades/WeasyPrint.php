<?php

namespace Fruitcake\WeasyPrint\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;
use Fruitcake\WeasyPrint\WeasyPrintWrapperFaker;

class WeasyPrint extends BaseFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'weasyprint.pdf.wrapper';
    }

    /**
     * Replace the bound instance with a fake.
     *
     * @return void
     */
    public static function fake()
    {
        static::swap(new WeasyPrintWrapperFaker(app('weasyprint.pdf')));
    }
}
