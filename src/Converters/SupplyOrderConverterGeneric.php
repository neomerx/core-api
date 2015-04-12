<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\SupplyOrder;
use \Neomerx\CoreApi\Api\SupplyOrders\SupplyOrders as Api;

class SupplyOrderConverterGeneric implements ConverterInterface
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param SupplyOrder $supplyOrder
     *
     * @return array
     */
    public function convert($supplyOrder = null)
    {
        if ($supplyOrder === null) {
            return null;
        }

        assert('$supplyOrder instanceof '.SupplyOrder::class);

        $result = $supplyOrder->attributesToArray();

        $result[Api::PARAM_CURRENCY_CODE]  = $supplyOrder->currency->code;
        $result[Api::PARAM_LANGUAGE_CODE]  = $supplyOrder->language->iso_code;
        $result[Api::PARAM_SUPPLIER_CODE]  = $supplyOrder->supplier->code;
        $result[Api::PARAM_WAREHOUSE_CODE] = $supplyOrder->warehouse->code;

        $details = [];
        /** @var SupplyOrderDetailsConverterGeneric $detailsConverter */
        $detailsConverter = app(SupplyOrderDetailsConverterGeneric::class);
        foreach ($supplyOrder->details as $detailsRow) {
            /** @var \Neomerx\Core\Models\SupplyOrderDetails $detailsRow */
            $details[] = $detailsConverter->convert($detailsRow);
        }
        $result[Api::PARAM_DETAILS] = $details;

        return $result;
    }
}
