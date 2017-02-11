<?php
/**
 * © 2017 Valiton GmbH
 */

namespace Pdf;

trait PdfLibOptionsTrait
{

    protected $lib;

    /**
     * Wrapper for PDFlib::set_option.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setOption($key, $value = true)
    {
        $this->lib->set_option($key . '=' . $this->formatOptionListValue($value));
    }

    /**
     * Convert an array to a PDFlib option list.
     *
     * @param array $options
     * @param array $defaults
     * @return string
     */
    protected function createOptionList(array $options, array $defaults = [])
    {
        $options = array_merge($defaults, $options);

        foreach ($options as $key => &$value) {
            if (is_int($key)) {
                $key = $value;
                $value = true;
            }

            $value = $key . '=' . $this->formatOptionListValue($value);
        }

        return implode(' ', $options);
    }

    /**
     * Format values for PDFlib option list.
     *
     * @param $value
     * @return string
     */
    protected function formatOptionListValue($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value)) {
            return '{' . implode(' ', $value) . '}';
        }

        return $value;
    }

}
