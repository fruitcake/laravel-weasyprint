<?php

namespace Fruitcake\WeasyPrint;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Contracts\Support\Renderable;
use Pontedilana\PhpWeasyPrint\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * A Laravel wrapper for WeasyPrint PDF
 *
 * @package laravel-weasyprint
 * @author Fruitcake
 */
class WeasyPrintWrapper
{
    /**
     * @var Pdf
     */
    protected $weasy;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var string
     */
    protected $html;

    /**
     * @var string
     */
    protected $file;

    /**
     * @param Pdf $weasy
     */
    public function __construct(Pdf $weasy)
    {
        $this->weasy = $weasy;
    }

    /**
     * Get the WeasyPrint instance.
     *
     * @return Pdf
     */
    public function weasy()
    {
        return $this->weasy;
    }

    /**
     * Set temporary folder
     *
     * @param  string $path
     */
    public function setTemporaryFolder($path)
    {
        $this->weasy->setTemporaryFolder($path);
        return $this;
    }

    /**
     * @param  string $name
     * @param  mixed $value
     * @return $this
     */
    public function setOption($name, $value)
    {
        if ($value instanceof Renderable) {
            $value = $value->render();
        }
        $this->weasy->setOption($name, $value);
        return $this;
    }

    /**
     * @param  array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->weasy->setOptions($options);
        return $this;
    }

    /**
     * Load a HTML string
     *
     * @param  Array|string|Renderable $html
     * @return $this
     */
    public function loadHTML($html)
    {
        if ($html instanceof Renderable) {
            $html = $html->render();
        }
        $this->html = $html;
        $this->file = null;
        return $this;
    }

    /**
     * Load a HTML file
     *
     * @param  string $file
     * @return $this
     */
    public function loadFile($file)
    {
        $this->html = null;
        $this->file = $file;
        return $this;
    }

    /**
     * Load a View and convert to HTML
     *
     * @param  string $view
     * @param  array $data
     * @param  array $mergeData
     * @return $this
     */
    public function loadView($view, $data = array(), $mergeData = array())
    {
        $view = View::make($view, $data, $mergeData);

        return $this->loadHTML($view);
    }

    /**
     * Output the PDF as a string.
     *
     * @return string The rendered PDF as string
     * @throws \InvalidArgumentException
     */
    public function output()
    {
        if ($this->html) {
            return $this->weasy->getOutputFromHtml($this->html, $this->options);
        }

        if ($this->file) {
            return $this->weasy->getOutput($this->file, $this->options);
        }

        throw new \InvalidArgumentException('PDF Generator requires a html or file in order to produce output.');
    }

    /**
     * Save the PDF to a file
     *
     * @param  $filename
     * @return $this
     */
    public function save($filename, $overwrite = false)
    {

        if ($this->html) {
            $this->weasy->generateFromHtml($this->html, $filename, $this->options, $overwrite);
        } elseif ($this->file) {
            $this->weasy->generate($this->file, $filename, $this->options, $overwrite);
        }

        return $this;
    }

    /**
     * Make the PDF downloadable by the user
     *
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function download($filename = 'document.pdf')
    {
        return new Response($this->output(), 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'attachment; filename="' . $filename . '"'
        ));
    }

    /**
     * Return a response with the PDF to show in the browser
     *
     * @param string $filename
     * @return \Illuminate\Http\Response
     */
    public function inline($filename = 'document.pdf')
    {
        return new Response($this->output(), 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ));
    }

    /**
     * Return a response with the PDF to show in the browser
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @deprecated use inline() instead
     */
    public function stream($filename = 'document.pdf')
    {
        return new StreamedResponse(function () {
            echo $this->output();
        }, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ));
    }

    /**
     * Call WeasyPrint instance.
     *
     * Also shortcut's
     * ->html => loadHtml
     * ->view => loadView
     * ->file => loadFile
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        $method = 'load' . ucfirst($name);
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $arguments);
        }

        return call_user_func_array(array($this->weasy, $name), $arguments);
    }
}
