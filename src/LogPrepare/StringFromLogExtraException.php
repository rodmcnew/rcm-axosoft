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
    protected $exceptionMethodsBlacklist;

    /**
     * @param StringFromArray $stringFromArray
     * @param array           $exceptionMethodsBlacklist
     */
    public function __construct(
        StringFromArray $stringFromArray,
        array $exceptionMethodsBlacklist
        = [
            'getTrace',
            'getPrevious',
            'getTraceAsString',
        ]
    ) {
        $this->stringFromArray = $stringFromArray;
        $this->exceptionMethodsBlacklist = $exceptionMethodsBlacklist;
    }

    /**
     *
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
        if (!isset($extra['exception'])) {
            return '';
        }

        $exception = $extra['exception'];

        if (!$exception instanceof \Throwable && !$exception instanceof \Exception) {
            return json_encode($exception);
        }

        $exceptionMethodsBlacklist = $this->getOption(
            $options,
            'exceptionMethodsBlacklist',
            $this->exceptionMethodsBlacklist
        );

        $return = [];
        $return['exception'] = get_class($exception);
        $methods = get_class_methods($exception);
        foreach ($methods as $method) {
            if (substr($method, 0, 3) === "get"
                && !in_array(
                    $method,
                    $exceptionMethodsBlacklist
                )
            ) {
                $return[$method] = $exception->$method();
            }
        }

        return $this->stringFromArray->__invoke($return, $options);
    }
}
