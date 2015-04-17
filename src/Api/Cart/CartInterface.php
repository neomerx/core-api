<?php namespace Neomerx\CoreApi\Api\Cart;

use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\OrderDetails;

/**
 * @package Neomerx\CoreApi
 */
interface CartInterface
{
    /** Parameter key */
    const PARAM_WEIGHT            = 'weight';
    /** Parameter key */
    const PARAM_QUANTITY          = OrderDetails::FIELD_QUANTITY;
    /** Parameter key */
    const PARAM_PRICE_WO_TAX      = OrderDetails::FIELD_PRICE_WO_TAX;
    /** Parameter key */
    const PARAM_MAX_DIMENSION     = 'max_dimension';
    /** Parameter key */
    const PARAM_ITEMS             = 'items';
    /** Parameter key */
    const PARAM_ITEM_SKU          = Variant::FIELD_SKU;
    /** Parameter key */
    const PARAM_ITEM_PRICE_WO_TAX = OrderDetails::FIELD_PRICE_WO_TAX;
    /** Parameter key */
    const PARAM_ITEM_QUANTITY     = OrderDetails::FIELD_QUANTITY;
}
