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
    |    See https://doc.courtbouillon.org/weasyprint/latest/api_reference.html#weasyprint.DEFAULT_OPTIONS
    |
    | Env:
    |
    |    The environment variables to set while running the weasyprint process.
    |
    */

    'pdf' => [
        'binary'  => env('WEASYPRINT_BINARY', '/usr/local/bin/weasyprint'),
        'timeout' => 10,    // Default timeout is 10 seconds
        'options' => [
            'encoding' => null,
            'stylesheet' => [], //An optional list of user stylesheets. The list can include are CSS objects, filenames, URLs, or file-like objects.s
            'media-type' => null,   //Media type to use for @media.
            'base-url' => null,
            'attachment' => [], //A list of additional file attachments for the generated PDF document
            'presentational-hints' => null,
            'pdf-identifier' => null,   // A bytestring used as PDF file identifier.
            'pdf-variant' => null,  // A PDF variant name.
            'pdf-version' => null,  // A PDF version number.
            'pdf-forms' => null,    // (bool) Whether PDF forms have to be included.
            'pdf-tags' => null,
            'custom-metadata' => null,
            'uncompressed-pdf' => null, //Whether PDF content should be compressed.
            'full-fonts' => null,
            'hinting' => null,
            'dpi' => null,
            'jpeg-quality' => null,
            'optimize-images' => null,
            'cache-folder' => null,
            'timeout' => null,
        ],
        'env'     => [],
    ],
];
