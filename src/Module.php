<?php

namespace Reliv\RcmAxosoft;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * Class Module
 */
class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
