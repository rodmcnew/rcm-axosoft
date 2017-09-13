<?php

namespace Reliv\RcmAxosoft\LogPrepare;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromLogUrl extends StringFromLogAbstract implements StringFromLog
{
    /**
     * @param mixed $priority
     * @param string $message
     * @param array $extra
     * @param array $options
     *
     * @return string
     */
    public function __invoke(
        $priority,
        string $message,
        array $extra = [],
        array $options = []
    ): string {
        $string = '';

        if (isset($_SERVER) && isset($_SERVER['REQUEST_METHOD'])) {
            $string .= $_SERVER['REQUEST_METHOD'] . ' ';
        }

        if (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) {
            $string .= $_SERVER['HTTP_HOST'];
        }

        if (isset($_SERVER) && isset($_SERVER['REQUEST_URI'])) {
            $string .= $_SERVER['REQUEST_URI'];
        }

        return $string;
    }
}
