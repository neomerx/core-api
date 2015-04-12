<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\CarrierCustomerType;

class CarrierCustomerTypeArgs extends EventArgs
{
    /**
     * @var CarrierCustomerType
     */
    private $model;

    /**
     * @param string              $name
     * @param CarrierCustomerType $model
     * @param EventArgs           $args
     */
    public function __construct($name, CarrierCustomerType $model, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->model = $model;
    }

    /**
     * @return CarrierCustomerType
     */
    public function getModel()
    {
        return $this->model;
    }
}
