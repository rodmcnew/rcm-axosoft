<?php

namespace Reliv\RcmAxosoft\LogPrepare;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromLogServerDump extends StringFromLogAbstract implements StringFromLog
{
    /**
     * @var StringFromArray
     */
    protected $stringFromArray;

    /**
     * @param StringFromArray $stringFromArray
     */
    public function __construct(
        StringFromArray $stringFromArray
    ) {
        $this->stringFromArray = $stringFromArray;
    }

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

        $includeServerDump = $this->getOption($options, 'includeServerDump', false);

        if (!$includeServerDump) {
            return '';
        }

        $string = '';

        if (isset($_SERVER) && $includeServerDump) {
            $string .= $lineBreak .
                $this->stringFromArray->__invoke(
                    $_SERVER,
                    $options
                );
        }

        return $string;
    }
}
