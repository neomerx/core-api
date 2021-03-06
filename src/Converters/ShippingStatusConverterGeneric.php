<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\ShippingOrderStatus;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

class ShippingStatusConverterGeneric implements ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param ShippingOrderStatus $shippingStatus
     *
     * @return array
     */
    public function convert($shippingStatus = null)
    {
        if ($shippingStatus === null) {
            return null;
        }

        ($shippingStatus instanceof ShippingOrderStatus) ?: S\throwEx(new InvalidArgumentException('shippingStatus'));

        $result = $shippingStatus->attributesToArray();

        return $result;
    }
}
