<?php

namespace Reliv\RcmAxosoft\LogPrepare;

/**
 * @author James Jervis - https://github.com/jerv13
 */
interface StringFromLog
{
    /**
     * @param        $priority
     * @param string $message
     * @param array  $extra
     * @param array  $options
     *
     * @return string
     */
    public function __invoke(
        $priority,
        string $message,
        array $extra = [],
        array $options = []
    ): string;
}
