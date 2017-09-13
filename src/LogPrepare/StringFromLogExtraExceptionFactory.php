<?php

namespace Reliv\RcmAxosoft\LogPrepare;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromLogExtraExceptionFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return StringFromLogExtraException
     */
    public function __invoke($container)
    {
        $configRoot = $container->get('Config');

        return new StringFromLogExtraException(
            $container->get(StringFromArray::class),
            $configRoot['Reliv\RcmAxosoft']['errorLogger']['exceptionMethodsBlacklist']
        );
    }
}
