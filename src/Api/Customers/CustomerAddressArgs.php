<?php namespace Neomerx\CoreApi\Api\Customers;

use \Neomerx\CoreApi\Events\EventArgs;
use \Neomerx\Core\Models\CustomerAddress;

class CustomerAddressArgs extends EventArgs
{
    /**
     * @var CustomerAddress
     */
    private $address;

    /**
     * @param string          $name
     * @param CustomerAddress $address
     * @param EventArgs       $args
     */
    public function __construct($name, CustomerAddress $address, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->address = $address;
    }

    /**
     * @return CustomerAddress
     */
    public function getModel()
    {
        return $this->address;
    }
}
