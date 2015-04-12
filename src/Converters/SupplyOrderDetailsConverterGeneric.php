<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\SupplyOrderDetails;
use \Neomerx\CoreApi\Api\SupplyOrders\SupplyOrderDetailsInterface as Api;

class SupplyOrderDetailsConverterGeneric implements ConverterInterface
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param SupplyOrderDetails $orderDetails
     *
     * @return array
     */
    public function convert($orderDetails = null)
    {
        if ($orderDetails === null) {
            return null;
        }

        assert('$orderDetails instanceof '.SupplyOrderDetails::class);

        $result                 = $orderDetails->attributesToArray();
        $result[Api::PARAM_SKU] = $orderDetails->{SupplyOrderDetails::FIELD_VARIANT}->{Variant::FIELD_SKU};

        return $result;
    }
}
