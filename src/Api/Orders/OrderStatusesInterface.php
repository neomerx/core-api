<?php namespace Neomerx\CoreApi\Api\Orders;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\OrderStatus;
use \Illuminate\Database\Eloquent\Collection;

interface OrderStatusesInterface extends CrudInterface
{
    const PARAM_CODE = OrderStatus::FIELD_CODE;
    const PARAM_NAME = OrderStatus::FIELD_NAME;

    /**
     * Create order status.
     *
     * @param array $input
     *
     * @return OrderStatus
     */
    public function create(array $input);

    /**
     * Read order status by identifier.
     *
     * @param string $code
     *
     * @return OrderStatus
     */
    public function read($code);

    /**
     * Search order statuses.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
