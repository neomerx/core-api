<?php namespace Neomerx\CoreApi\Api\SupplyOrders;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\SupplyOrder;
use \Illuminate\Database\Eloquent\Collection;

interface SupplyOrdersInterface extends CrudInterface
{
    const PARAM_SUPPLIER_CODE  = 'supplier_code';
    const PARAM_WAREHOUSE_CODE = 'warehouse_code';
    const PARAM_CURRENCY_CODE  = 'currency_code';
    const PARAM_LANGUAGE_CODE  = 'language_code';
    const PARAM_EXPECTED_AT    = SupplyOrder::FIELD_EXPECTED_AT;
    const PARAM_STATUS         = SupplyOrder::FIELD_STATUS;

    const PARAM_DETAILS                 = SupplyOrder::FIELD_DETAILS;
    const PARAM_DETAILS_ID              = SupplyOrderDetailsInterface::PARAM_ID;
    const PARAM_DETAILS_ID_SUPPLY_ORDER = SupplyOrderDetailsInterface::PARAM_ID_SUPPLY_ORDER;
    const PARAM_DETAILS_SKU             = SupplyOrderDetailsInterface::PARAM_SKU;
    const PARAM_DETAILS_PRICE_WO_TAX    = SupplyOrderDetailsInterface::PARAM_PRICE_WO_TAX;
    const PARAM_DETAILS_QUANTITY        = SupplyOrderDetailsInterface::PARAM_QUANTITY;
    const PARAM_DETAILS_DISCOUNT_RATE   = SupplyOrderDetailsInterface::PARAM_DISCOUNT_RATE;
    const PARAM_DETAILS_TAX_RATE        = SupplyOrderDetailsInterface::PARAM_TAX_RATE;

    /**
     * Create supply order.
     *
     * @param array $input
     *
     * @return SupplyOrder
     */
    public function create(array $input);

    /**
     * Read supply order by identifier.
     *
     * @param int $supplyOrderId
     *
     * @return SupplyOrder
     */
    public function read($supplyOrderId);

    /**
     * Search supply orders.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
