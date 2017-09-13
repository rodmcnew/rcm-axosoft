<?php

namespace Reliv\RcmAxosoft\LogPrepare;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SummaryFromLogBasicFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return SummaryFromLogBasic
     */
    public function __invoke($container)
    {
        return new SummaryFromLogBasic();
    }
}
