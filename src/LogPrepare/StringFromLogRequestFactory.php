<?php

namespace Reliv\RcmAxosoft\LogPrepare;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromLogRequestFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return StringFromLogRequest
     */
    public function __invoke($container)
    {
        return new StringFromLogRequest();
    }
}
