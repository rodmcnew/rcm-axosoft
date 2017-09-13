<?php
/**
 * dependencies.php
 */
return [
    'factories' => [
        /*
         * Log ======================================
         */
        \Reliv\RcmAxosoft\Log\AxosoftLoggerPsr::class
        => \Reliv\RcmAxosoft\Log\AxosoftLoggerPsrFactory::class,

        /*
         * LogPrepare ======================================
         */
        // Default service
        \Reliv\RcmAxosoft\LogPrepare\DescriptionFromLog::class
        => \Reliv\RcmAxosoft\LogPrepare\DescriptionFromLogAllFactory::class,

        \Reliv\RcmAxosoft\LogPrepare\DescriptionFromLogAll::class
        => \Reliv\RcmAxosoft\LogPrepare\DescriptionFromLogAllFactory::class,

        // Default service
        \Reliv\RcmAxosoft\LogPrepare\StringFromArray::class
        => \Reliv\RcmAxosoft\LogPrepare\StringFromArrayBasicFactory::class,

        \Reliv\RcmAxosoft\LogPrepare\StringFromArrayBasic::class
        => \Reliv\RcmAxosoft\LogPrepare\StringFromArrayBasicFactory::class,

        \Reliv\RcmAxosoft\LogPrepare\StringFromLogExtraException::class
        => \Reliv\RcmAxosoft\LogPrepare\StringFromLogExtraExceptionFactory::class,

        \Reliv\RcmAxosoft\LogPrepare\StringFromLogUrl::class
        => \Reliv\RcmAxosoft\LogPrepare\StringFromLogUrlFactory::class,

        \Reliv\RcmAxosoft\LogPrepare\StringFromLogServerDump::class
        => \Reliv\RcmAxosoft\LogPrepare\StringFromLogServerDumpFactory::class,

        \Reliv\RcmAxosoft\LogPrepare\StringFromLogSession::class
        => \Reliv\RcmAxosoft\LogPrepare\StringFromLogSessionFactory::class,

        // Default service
        \Reliv\RcmAxosoft\LogPrepare\SummaryFromLog::class
        => \Reliv\RcmAxosoft\LogPrepare\SummaryFromLogBasicFactory::class,

        \Reliv\RcmAxosoft\LogPrepare\SummaryFromLogBasic::class
        => \Reliv\RcmAxosoft\LogPrepare\SummaryFromLogBasicFactory::class,
    ],
];
