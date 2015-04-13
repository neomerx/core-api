<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\OrderStatus;

class OrderStatusConverterGeneric implements ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param OrderStatus $orderStatus
     *
     * @return array
     */
    public function convert($orderStatus = null)
    {
        if ($orderStatus === null) {
            return null;
        }

        assert('$orderStatus instanceof '.OrderStatus::class);

        $result = $orderStatus->attributesToArray();

        return $result;
    }
}
