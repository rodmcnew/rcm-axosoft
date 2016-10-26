<?php

namespace Reliv\RcmAxosoft\Log;

use Reliv\AxosoftApi\V5\ApiCreate\AbstractApiRequestCreate;

/**
 * Class ItemTypeCreateMap
 *
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt
 * @link      https://github.com/reliv
 */
class ItemTypeCreateMap
{
    /**
     * @var array
     */
    protected static $itemTypeCreateMap
        = [
            'defect' => 'Reliv\AxosoftApi\V5\Items\Defects\ApiRequestCreate',
            'incident' => 'Reliv\AxosoftApi\V5\Items\Incidents\ApiRequestCreate',
            'feature' => 'Reliv\AxosoftApi\V5\Items\Features\ApiRequestCreate',
            'task' => 'Reliv\AxosoftApi\V5\Items\Tasks\ApiRequestCreate',
        ];

    /**
     * getItemTypeClass
     *
     * @param string $itemType
     *
     * @return mixed
     */
    public static function getItemTypeClass($itemType = 'defect')
    {
        $itemClass = self::$itemTypeCreateMap['defect'];

        if (isset(self::$itemTypeCreateMap[$itemType])) {
            $itemClass = self::$itemTypeCreateMap[$itemType];
        }

        return $itemClass;
    }

    /**
     * getItemObject
     *
     * @param string $itemType
     *
     * @return AbstractApiRequestCreate
     */
    public static function getItemObject($itemType = 'defect')
    {
        $itemClass = self::getItemTypeClass($itemType);

        return new $itemClass();
    }
}
