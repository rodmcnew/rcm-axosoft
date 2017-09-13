<?php

namespace Reliv\RcmAxosoft\LogPrepare;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class DescriptionFromLogAllFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return DescriptionFromLogAll
     */
    public function __invoke($container)
    {
        return new DescriptionFromLogAll(
            $container->get(StringFromLogRequest::class),
            $container->get(StringFromLogExtraException::class),
            $container->get(StringFromLogServerDump::class),
            $container->get(StringFromLogSession::class)
        );
    }
}
