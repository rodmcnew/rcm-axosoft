<?php

namespace Reliv\RcmAxosoft\LogPrepare;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromLogServerDumpFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return StringFromLogServerDump
     */
    public function __invoke($container)
    {
        return new StringFromLogServerDump(
            $container->get(StringFromArray::class)
        );
    }
}
