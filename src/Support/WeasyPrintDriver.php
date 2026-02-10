<?php

namespace Fruitcake\WeasyPrint\Support;

use Pontedilana\PhpWeasyPrint\Pdf;
use Spatie\LaravelPdf\Drivers\PdfDriver;
use Spatie\LaravelPdf\PdfOptions;

class WeasyPrintDriver implements PdfDriver
{
    public function __construct(protected readonly Pdf $weasy)
    {

    }

    public function generatePdf(
        string $html,
        ?string $headerHtml,
        ?string $footerHtml,
        PdfOptions $options,
    ): string {
        $html = $this->mergeHeaderFooter($html, $headerHtml, $footerHtml);

        return $this->weasy->getOutputFromHtml($html, $this->prepareOptions($options));
    }

    public function savePdf(
        string $html,
        ?string $headerHtml,
        ?string $footerHtml,
        PdfOptions $options,
        string $path,
    ): void {
        $html = $this->mergeHeaderFooter($html, $headerHtml, $footerHtml);

        $this->weasy->generateFromHtml($html, $path, $this->prepareOptions($options));
    }

    /**
     * Based on the laravel-pdf DompdfDriver, but the footer block is BEFORE the rest of the body,
     * so it renders the running element correctly.
     *
     * @param string $html
     * @param string|null $headerHtml
     * @param string|null $footerHtml
     * @return string
     *
     * @see \Spatie\LaravelPdf\Drivers\DomPdfDriver::mergeHeaderFooter
     */
    protected function mergeHeaderFooter(string $html, ?string $headerHtml, ?string $footerHtml): string
    {
        if (! $headerHtml && ! $footerHtml) {
            return $html;
        }

        $headerBlock = $headerHtml
            ? '<div class="pdf-header">'.$headerHtml.'</div>'
            : '';

        $footerBlock = $footerHtml
            ? '<div class="pdf-footer">'.$footerHtml.'</div>'
            : '';

        if (preg_match('/<body([^>]*)>/i', $html, $matches)) {
            $html = preg_replace(
                '/<body([^>]*)>/i',
                '<body$1>'.$headerBlock.$footerBlock,
                $html,
            );

            return $html;
        }

        return $headerBlock.$footerBlock.$html;
    }

    /**
     * Based on DomPdfDriver::injectMarginCss but also adds the header/footer as running elements.
     *
     *
     * @see \Spatie\LaravelPdf\Drivers\DomPdfDriver::injectMarginCss
     */
    protected function prepareOptions(PdfOptions $options): array
    {
        $marginCss = '';
        if ($options->margins) {
            $unit = $options->margins['unit'] ?? 'mm';
            $top = $options->margins['top'] . $unit;
            $right = $options->margins['right'] . $unit;
            $bottom = $options->margins['bottom'] . $unit;
            $left = $options->margins['left'] . $unit;

            $marginCss = "margin: {$top} {$right} {$bottom} {$left};";
        }

        $sizeCss = '';
        if ($options->paperSize) {
            $unit = $options->paperSize['unit'] ?? 'mm';
            $sizeWidth = $options->paperSize['width'].$unit;
            $sizeHeight = $options->paperSize['height'].$unit;
            $sizeCss = "size: {$sizeWidth} {$sizeHeight};";
        } elseif ($options->format || $options->orientation) {
            $format = strtolower($options->format);
            $orientation = strtolower($options->orientation);
            $sizeCss = "size: {$format} {$orientation};";
        }

        $stylesheet = <<<CSS
@page {
    {$sizeCss}
    {$marginCss}

    @top-left {
        content: element(pdfHeader);
    }
    @bottom-left {
        content: element(pdfFooter);
    }
}
.pdf-header {
    position: running(pdfHeader);
}
.pdf-footer {
    position: running(pdfFooter);
}

.pageNumber::before { content: counter(page); }
.totalPages::before { content: counter(pages); }
CSS;

        return [
            'stylesheet' => $stylesheet,
        ];
    }
}
