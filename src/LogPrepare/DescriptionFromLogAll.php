<?php

namespace Reliv\RcmAxosoft\LogPrepare;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class DescriptionFromLogAll extends StringFromLogAbstract implements DescriptionFromLog
{
    /**
     * @var StringFromLogRequest
     */
    protected $stringFromLogRequest;

    /**
     * @var StringFromLogExtraException
     */
    protected $stringFromLogExtraException;

    /**
     * @var StringFromLogServerDump
     */
    protected $stringFromLogServerDump;

    /**
     * @var StringFromLogSession
     */
    protected $stringFromLogSession;

    /**
     * @param StringFromLogRequest        $stringFromLogRequest
     * @param StringFromLogExtraException $stringFromLogExtraException
     * @param StringFromLogServerDump     $stringFromLogServerDump
     * @param StringFromLogSession        $stringFromLogSession
     */
    public function __construct(
        StringFromLogRequest $stringFromLogRequest,
        StringFromLogExtraException $stringFromLogExtraException,
        StringFromLogServerDump $stringFromLogServerDump,
        StringFromLogSession $stringFromLogSession
    ) {
        $this->stringFromLogRequest = $stringFromLogRequest;
        $this->stringFromLogExtraException = $stringFromLogExtraException;
        $this->stringFromLogServerDump = $stringFromLogServerDump;
        $this->stringFromLogSession = $stringFromLogSession;
    }

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
    ): string {
        $lineBreak = $this->getOption($options, 'lineBreak', "\n");

        if (isset($extra['description'])) {
            $description = $extra['description'] . $lineBreak;
        } else {
            $description = $message . $lineBreak;
        }

        $description .= 'Level: ' . $priority . $lineBreak;

        $requestString = $this->stringFromLogRequest->__invoke(
            $priority,
            $message,
            $extra,
            $options
        );

        if (!empty($requestString)) {
            $description .= 'Request ---- ' . $lineBreak . $requestString . $lineBreak;
        }

        if (isset($extra['file'])) {
            $description .= 'File: ' . $extra['file'] . $lineBreak;
        }

        if (isset($extra['line'])) {
            $description .= 'Line: ' . $extra['line'] . $lineBreak;
        }

        if (isset($extra['message'])) {
            $description .= 'Message: ' . $extra['message'] . $lineBreak;
        }

        if (isset($extra['trace'])) {
            $stackTrack = str_replace("\n", $lineBreak, $extra['trace']);
            $description .= 'Stack trace: ' . $lineBreak . $stackTrack;
        }

        $exceptionString = $this->stringFromLogExtraException->__invoke(
            $priority,
            $message,
            $extra,
            $options
        );

        if (!empty($exceptionString)) {
            $description .= 'Exception: ' . $exceptionString . $lineBreak;
        }

        $serverString = $this->stringFromLogServerDump->__invoke(
            $priority,
            $message,
            $extra,
            $options
        );

        if (!empty($serverString)) {
            $description .= 'Server: ' . $serverString . $lineBreak;
        }

        $sessionString = $this->stringFromLogSession->__invoke(
            $priority,
            $message,
            $extra,
            $options
        );

        if (!empty($sessionString)) {
            $description .= ' Session: ' . $sessionString . $lineBreak;
        }

        return $description;
    }
}
