<?php

namespace Reliv\RcmAxosoft\Log;

use Psr\Container\ContainerInterface;
use Reliv\AxosoftApi\Service\AxosoftApi;
use Reliv\RcmAxosoft\LogPrepare\DescriptionFromLog;
use Reliv\RcmAxosoft\LogPrepare\SummaryFromLog;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class AxosoftLoggerPsrFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return AxosoftLoggerPsr
     */
    public function __invoke($container)
    {
        $configRoot = $container->get('Config');

        /** @var AxosoftApi $api */
        $api = $container->get('Reliv\AxosoftApi\Service\AxosoftApi');

        return new AxosoftLoggerPsr(
            $api,
            $container->get(DescriptionFromLog::class),
            $container->get(SummaryFromLog::class),
            $configRoot['Reliv\RcmAxosoft']['errorLogger']
        );
    }
}
