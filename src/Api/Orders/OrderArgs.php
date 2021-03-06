<?php namespace Neomerx\CoreApi\Api\Orders;

use \Neomerx\Core\Models\Order;
use \Neomerx\CoreApi\Events\EventArgs;

/**
 * @package Neomerx\CoreApi
 */
class OrderArgs extends EventArgs
{
    /**
     * @var Order
     */
    private $order;

    /**
     * @param string    $name
     * @param Order     $order
     * @param EventArgs $args
     */
    public function __construct($name, Order $order, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getModel()
    {
        return $this->order;
    }
}
