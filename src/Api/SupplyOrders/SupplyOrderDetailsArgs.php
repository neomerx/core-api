<?php namespace Neomerx\CoreApi\Api\SupplyOrders;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\SupplyOrderDetails as Model;

class SupplyOrderDetailsArgs extends EventArgs
{
    /**
     * @var Model
     */
    private $supplyOrderDetails;

    /**
     * @param string    $name
     * @param Model     $supplyOrderDetails
     * @param EventArgs $args
     */
    public function __construct($name, Model $supplyOrderDetails, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->supplyOrderDetails = $supplyOrderDetails;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->supplyOrderDetails;
    }
}
