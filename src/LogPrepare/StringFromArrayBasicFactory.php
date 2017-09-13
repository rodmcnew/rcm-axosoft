<?php

namespace Reliv\RcmAxosoft\LogPrepare;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class StringFromArrayBasicFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return StringFromArrayBasic
     */
    public function __invoke($container)
    {
        return new StringFromArrayBasic();
    }
}
