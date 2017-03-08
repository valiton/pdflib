<?php
/**
 * © 2017 Valiton GmbH
 */

namespace Pdf;

use TET;
use TETException;

class PdfLibTetAdapter
{

    use PdfLibOptionsTrait;

    /**
     * @var TET
     */
    protected $lib;

    /**
     * @param TET $lib
     * @param string $licenseKey
     */
    public function __construct(TET $lib, string $licenseKey)
    {
        $this->lib = $lib;
        $this->setOption('license', $licenseKey);
    }

    function __call($name, $arguments)
    {
        // TODO remove when all methods have been implemented
        if (method_exists($this->lib, $name)) {
            return $this->lib->$name(...$arguments);
        }
        throw new \BadMethodCallException(sprintf('Method %s does not exist', $name));
    }

    /**
     * Wrapper for TET::open_document.
     *
     * @param $filename
     * @param array $options
     * @return int
     * @throws \TETException
     */
    public function openDocument($filename, $options = [])
    {
        if (-1 !== ($handle = $this->lib->open_document($filename, $this->createOptionList($options)))) {
            return $handle;
        }
        throw new TETException($this->lib->get_errmsg(), $this->lib->get_errnum());
    }

    /**
     * Wrapper for PDFlib::open_pdi_page.
     *
     * @param Handleable|int $document
     * @param int $page
     * @param array $options
     * @return int
     */
    public function openPage($document, $page, $options = [])
    {
        return $this->lib->open_page($this->getHandleFrom($document), $page, $this->createOptionList($options));
    }

    /**
     * Wrapper for TET::pcos_get_number.
     *
     * @param Handleable|int $document
     * @param string $path
     * @return float
     */
    public function pcosGetNumber($document, $path)
    {
        return $this->lib->pcos_get_number($this->getHandleFrom($document), $path);
    }

    /**
     * Wrapper for TET::pcos_get_string.
     *
     * @param Handleable|int $document
     * @param string $path
     * @return string
     */
    public function pcosGetString($document, $path)
    {
        return $this->lib->pcos_get_string($this->getHandleFrom($document), $path);
    }

    /**
     * Wrapper for TET::pcos_get_stream.
     *
     * @param Handleable|int $document
     * @param string $path
     * @param array $options
     * @return string
     */
    public function pcosGetStream($document, $path, $options = [])
    {
        return $this->lib->pcos_get_stream($this->getHandleFrom($document), $this->createOptionList($options), $path);
    }

    /**
     * Get the PDFlib handle.
     *
     * @param Handleable|int $source
     * @return int
     */
    protected function getHandleFrom($source)
    {
        return ($source instanceof Handleable) ? $source->getHandle() : $source;
    }

    /**
     * @return \TET
     */
    public function getLib()
    {
        return $this->lib;
    }

}
