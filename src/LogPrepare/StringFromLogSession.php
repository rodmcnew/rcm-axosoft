<?php

namespace Reliv\RcmAxosoft\LogPrepare;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromLogSession extends StringFromLogAbstract implements StringFromLog
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
    ): string
    {
        $includeSessionVars = $this->getOption($options, 'includeSessionVars', null);

        if (!isset($_SESSION) || empty($includeSessionVars)) {
            return '';
        }

        $sessionVars = [];

        $session = $_SESSION;

        if (is_array($includeSessionVars)) {
            $sessionVarKeys = $includeSessionVars;
            foreach ($sessionVarKeys as $key) {
                if (isset($session[$key])) {
                    $sessionVars[$key] = $session[$key];
                }
            }
        }

        if ($includeSessionVars == 'ALL') {
            $sessionVars = $session;
        }

        return $this->stringFromArray->__invoke($sessionVars, $options);
    }
}
