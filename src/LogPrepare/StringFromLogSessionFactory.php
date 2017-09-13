<?php

namespace Reliv\RcmAxosoft\LogPrepare;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromLogSessionFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return StringFromLogSession
     */
    public function __invoke($container)
    {
        return new StringFromLogSession(
            $container->get(StringFromArray::class)
        );
    }
}
