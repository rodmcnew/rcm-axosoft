<?php

namespace Reliv\RcmAxosoft\LogPrepare;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromLogAbstract
{
    /**
     * @param array  $options
     * @param string $key
     * @param null   $default
     *
     * @return mixed|null
     */
    protected function getOption(array $options, $key, $default = null)
    {
        if (array_key_exists($key, $options)) {
            return $options[$key];
        }

        return $default;
    }
}
