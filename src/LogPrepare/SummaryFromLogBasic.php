<?php

namespace Reliv\RcmAxosoft\LogPrepare;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SummaryFromLogBasic extends StringFromLogAbstract implements SummaryFromLog
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
        $preprocessors = $this->getOption($options, 'summaryPreprocessors', []);

        foreach ($preprocessors as $pattern => $replacement) {
            $message = preg_replace($pattern, $replacement, $message);
        }

        $summary = strtoupper($priority) . ': ' . $message;

        $summary = substr($summary, 0, 255);

        $summary = str_replace(
            [
                "\r",
                "\n"
            ],
            ' ',
            $summary
        );

        // Limit is 150 chars, we add quotes and dots, so we have 145 chars left
        $summary = substr($summary, 0, 145) . '...';

        return $summary;
    }
}
