<?php

namespace Reliv\RcmAxosoft\LogPrepare;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromLogUrlFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return StringFromLogUrl
     */
    public function __invoke($container)
    {
        return new StringFromLogUrl();
    }
}
