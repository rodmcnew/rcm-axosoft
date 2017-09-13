<?php

namespace Reliv\RcmAxosoft\LogPrepare;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromLogExtraException extends StringFromLogAbstract implements StringFromLog
{
    /**
     * @var StringFromArray
     */
    protected $stringFromArray;

    /**
     * @var array
     */
    protected $exceptionMethodsToCallWhiteList;

    /**
     * @param StringFromArray $stringFromArray
     * @param array $exceptionMethodsToCallWhiteList
     */
    public function __construct(
        StringFromArray $stringFromArray,
        array $exceptionMethodsToCallWhiteList = []
    ) {
        $this->stringFromArray = $stringFromArray;
        $this->exceptionMethodsToCallWhiteList = $exceptionMethodsToCallWhiteList;
    }

    /**
     *
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
        if (!isset($extra['exception'])) {
            return '';
        }

        $lineBreak = $this->getOption($options, 'lineBreak', "\n");

        $exception = $extra['exception'];

        if (!$exception instanceof \Throwable && !$exception instanceof \Exception) {
            return json_encode($exception);
        }

        $exceptionMethodsToCallWhiteList = $this->getOption(
            $options,
            'exceptionMethodsToCallWhiteList',
            $this->exceptionMethodsToCallWhiteList
        );

        $return = [];
        $return['exception'] = get_class($exception);
        $methods = get_class_methods($exception);
        foreach ($methods as $method) {
            if (in_array($method, $exceptionMethodsToCallWhiteList)
            ) {
                $return[$method] = str_replace("\n", $lineBreak, $exception->$method());
            }
        }

        return $this->stringFromArray->__invoke($return, $options);
    }
}
