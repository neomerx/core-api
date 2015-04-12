<?php namespace Neomerx\CoreApi\Api\Customers;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\CustomerRisk;

class CustomerRiskArgs extends EventArgs
{
    /**
     * @var CustomerRisk
     */
    private $customerType;

    /**
     * @param string       $name
     * @param CustomerRisk $customerType
     * @param EventArgs    $args
     */
    public function __construct($name, CustomerRisk $customerType, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->customerType = $customerType;
    }

    /**
     * @return CustomerRisk
     */
    public function getModel()
    {
        return $this->customerType;
    }
}
