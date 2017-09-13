<?php

namespace Reliv\RcmAxosoft\LogPrepare;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromArrayBasic implements StringFromArray
{
    /**
     * @param array  $array
     * @param array  $options
     *
     * @return string
     */
    public function __invoke(
        array $array,
        array $options = []
    ):string {
        $lineBreak = (array_key_exists('lineBreak', $options) ? $options['lineBreak'] : "\n");

        $output = '';

        foreach ($array as $key => $val) {
            if (is_string($val)) {
                $output .= ' - ' . $key . ' = "' . $val . '"' . $lineBreak;
            } elseif (is_numeric($val)) {
                $output .= ' - ' . $key . ' = ' . $val . $lineBreak;
            } elseif (is_null($val)) {
                $output .= ' - ' . $key . " = NULL" . $lineBreak;
            } elseif (is_bool($val)) {
                $output
                    .= ' - ' . $key . ' = ' . ($val ? 'TRUE' : 'FALSE') . $lineBreak;
            } else {
                $output .= ' - ' . $key . ' = (' . gettype($val) . ") " . $lineBreak
                    . '{code}' . print_r($val, true) . "{code}" . $lineBreak;
            }
        }

        return $output;
    }
}
