<?php namespace Neomerx\CoreApi\Api\Orders;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\OrderStatusRule;

class OrderStatusRuleArgs extends EventArgs
{
    /**
     * @var OrderStatusRule
     */
    private $orderStatusRule;

    /**
     * @param string          $name
     * @param OrderStatusRule $orderStatusRule
     * @param EventArgs       $args
     */
    public function __construct($name, OrderStatusRule $orderStatusRule, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->orderStatusRule = $orderStatusRule;
    }

    /**
     * @return OrderStatusRule
     */
    public function getModel()
    {
        return $this->orderStatusRule;
    }
}
