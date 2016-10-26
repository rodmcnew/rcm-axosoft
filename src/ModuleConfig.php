<?php

namespace Reliv\RcmAxosoft;

/**
 * Class ModuleConfig
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Reliv\RcmAxosoft
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ModuleConfig
{
    /**
     * __invoke
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => require(__DIR__ . '/../config/dependencies.php'),
            'Reliv\RcmAxosoft' => require(__DIR__ . '/../config/reliv.rcm-axosoft.php'),
        ];
    }
}
