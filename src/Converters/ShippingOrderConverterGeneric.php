<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\ShippingOrder;
use \Neomerx\Core\Exceptions\InvalidArgumentException;
use \Neomerx\CoreApi\Api\ShippingOrders\ShippingOrdersInterface as Api;

class ShippingOrderConverterGeneric implements ConverterInterface
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param ShippingOrder $shippingOrder
     *
     * @return array
     */
    public function convert($shippingOrder = null)
    {
        if ($shippingOrder === null) {
            return null;
        }

        ($shippingOrder instanceof ShippingOrder) ?: S\throwEx(new InvalidArgumentException('shippingOrder'));

        $result = $shippingOrder->attributesToArray();

        $result[Api::PARAM_STATUS_CODE]  = $shippingOrder->status->code;
        $result[Api::PARAM_CARRIER_CODE] = $shippingOrder->carrier->code;

        return $result;
    }
}
