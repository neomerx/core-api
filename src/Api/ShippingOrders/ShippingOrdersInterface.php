<?php namespace Neomerx\CoreApi\Api\ShippingOrders;

use \Neomerx\Core\Models\Carrier;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\CoreApi\Api\Carriers\Tariff;
use \Neomerx\Core\Models\ShippingOrder;
use \Neomerx\CoreApi\Api\Carriers\ShippingData;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface ShippingOrdersInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_CARRIER_CODE    = 'carrier_code';
    /** Parameter key */
    const PARAM_STATUS_CODE     = 'status_code';
    /** Parameter key */
    const PARAM_TRACKING_NUMBER = ShippingOrder::FIELD_TRACKING_NUMBER;

    /**
     * Create shipping order.
     *
     * @param array $input
     *
     * @return ShippingOrder
     */
    public function create(array $input);

    /**
     * Read shipping order by identifier.
     *
     * @param int $shippingOrderId
     *
     * @return ShippingOrder
     */
    public function read($shippingOrderId);

    /**
     * Update shipping order.
     *
     * @param int   $shippingOrderId
     * @param array $input
     *
     * @return void
     */
    public function update($shippingOrderId, array $input);

    /**
     * Delete shipping order by identifier.
     *
     * @param int $shippingOrderId
     *
     * @return void
     */
    public function delete($shippingOrderId);

    /**
     * Search shipping orders.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);

    /**
     * Calculate shipping costs and taxes.
     *
     * @param ShippingData $shippingData
     * @param Carrier      $carrier
     *
     * @return Tariff
     */
    public function calculateCosts(ShippingData $shippingData, Carrier $carrier);
}
