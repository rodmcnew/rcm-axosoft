<?php

namespace Reliv\RcmAxosoft\Log;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @deprecated
 */
class AxosoftLoggerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configRoot = $serviceLocator->get('Config');
        $loggerOptions = $configRoot['Reliv\RcmAxosoft']['errorLogger'];

        $api = $serviceLocator->get('Reliv\AxosoftApi\Service\AxosoftApi');

        return new AxosoftLogger($api, $loggerOptions);
    }
}
