<?php

namespace Reliv\RcmAxosoft\Factory;

use Interop\Container\ContainerInterface;
use Reliv\RcmAxosoft\Log\AxosoftLogger;
use Reliv\RcmAxosoft\Log\AxosoftLoggerPsr;

/**
 * Class AxosoftLoggerPsrFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmAxosoft
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
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
        $loggerOptions = $configRoot['Reliv\RcmAxosoft']['errorLogger'];

        $api = $container->get('Reliv\AxosoftApi\Service\AxosoftApi');

        return new AxosoftLoggerPsr($api, $loggerOptions);
    }
}
