<?php

namespace Reliv\RcmAxosoft\LogPrepare;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromLogRequest extends StringFromLogAbstract implements StringFromLog
{
    /**
     * @param mixed  $priority
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
    ): string {
        $lineBreak = $this->getOption($options, 'lineBreak', "\n");
        $string = '';

        if (isset($_SERVER) && isset($_SERVER['HTTP_HOST'])) {
            $string .= 'HOST: ' . $_SERVER['HTTP_HOST'] . $lineBreak;
        }

        if (isset($_SERVER) && isset($_SERVER['REQUEST_URI'])) {
            $string .= 'URL: ' . $_SERVER['REQUEST_URI'] . $lineBreak;
        }

        return $string;
    }
}
