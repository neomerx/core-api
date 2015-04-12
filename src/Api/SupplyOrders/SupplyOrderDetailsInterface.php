<?php namespace Neomerx\CoreApi\Api\SupplyOrders;

use \Neomerx\Core\Models\Variant;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\SupplyOrder;
use \Neomerx\Core\Models\SupplyOrderDetails;
use \Illuminate\Database\Eloquent\Collection;

interface SupplyOrderDetailsInterface extends CrudInterface
{
    const PARAM_ID              = SupplyOrderDetails::FIELD_ID;
    const PARAM_ID_SUPPLY_ORDER = SupplyOrder::FIELD_ID;
    const PARAM_SKU             = Variant::FIELD_SKU;
    const PARAM_PRICE_WO_TAX    = SupplyOrderDetails::FIELD_PRICE_WO_TAX;
    const PARAM_QUANTITY        = SupplyOrderDetails::FIELD_QUANTITY;
    const PARAM_DISCOUNT_RATE   = SupplyOrderDetails::FIELD_DISCOUNT_RATE;
    const PARAM_TAX_RATE        = SupplyOrderDetails::FIELD_TAX_RATE;

    /**
     * Create supply order details.
     *
     * @param array $input
     *
     * @return SupplyOrderDetails
     */
    public function create(array $input);

    /**
     * Read supply order details by identifier.
     *
     * @param int $idx
     *
     * @return SupplyOrderDetails
     */
    public function read($idx);

    /**
     * Create supply order details.
     *
     * @param SupplyOrder $supplyOrder
     * @param array       $input
     *
     * @return SupplyOrderDetails
     */
    public function createWithOrder(SupplyOrder $supplyOrder, array $input);

    /**
     * Read supply order details by identifier.
     *
     * @param int $supplyOrderId
     * @param int $detailsId
     *
     * @return SupplyOrderDetails
     */
    public function readWithOrder($supplyOrderId, $detailsId);

    /**
     * Delete supply order details by identifier.
     *
     * @param int $supplyOrderId
     * @param int $detailsId
     *
     * @return void
     */
    public function deleteWithOrder($supplyOrderId, $detailsId);

    /**
     * Search supply order details.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
