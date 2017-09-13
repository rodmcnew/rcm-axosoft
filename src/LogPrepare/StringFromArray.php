<?php

namespace Reliv\RcmAxosoft\LogPrepare;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface StringFromArray
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
    ):string;
}
