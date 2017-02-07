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

}