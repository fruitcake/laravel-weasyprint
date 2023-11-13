<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WeasyPrint Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |    |
    | Binary:
    |
    |    The file path of the WeasyPrint executable.
    |
    | Timout:
    |
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The WeasyPrint command options.
    |    See https://doc.courtbouillon.org/weasyprint/latest/api_reference.html#command-line-api
    |
    | Env:
    |
    |    The environment variables to set while running the weasyprint process.
    |
    */

    'pdf' => [
        'binary'  => env('WEASYPRINT_BINARY', '/usr/local/bin/weasyprint'),
        'timeout' => false,
        'options' => [
            'encoding' => null,
            'stylesheet' => [],
            'media-type' => null,
            'base-url' => null,
            'attachment' => [], 
            'presentational-hints' => null,
            'pdf-identifier' => null,
            'pdf-variant' => null,
            'pdf-version' => null,
            'pdf-forms' => null,
            'custom-metadata' => null,
            'uncompressed-pdf' => null,
            'full-fonts' => null,
            'hinting' => null,
            'dpi' => null,
            'jpeg-quality' => null,
            'optimize-images' => null,
            'cache-folder' => null,
            'timeout' => null,
            // Deprecated
            'format' => null,
            'resolution' => null,
            'optimize-size' => null,
        ],
        'env'     => [],
    ],
];
