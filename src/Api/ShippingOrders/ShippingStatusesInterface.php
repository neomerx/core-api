<?php namespace Neomerx\CoreApi\Api\ShippingOrders;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ShippingOrderStatus;
use \Illuminate\Database\Eloquent\Collection;

interface ShippingStatusesInterface extends CrudInterface
{
    const PARAM_CODE = ShippingOrderStatus::FIELD_CODE;
    const PARAM_NAME = ShippingOrderStatus::FIELD_NAME;

    /**
     * Create shipping order status.
     *
     * @param array $input
     *
     * @return ShippingOrderStatus
     */
    public function create(array $input);

    /**
     * Read shipping order status by identifier.
     *
     * @param string $code
     *
     * @return ShippingOrderStatus
     */
    public function read($code);

    /**
     * Search shipping order statuses.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
