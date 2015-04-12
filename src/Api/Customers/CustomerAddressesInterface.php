<?php namespace Neomerx\CoreApi\Api\Customers;

use \Neomerx\Core\Models\Address;
use \Neomerx\Core\Models\Customer;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CustomerAddress;
use \Illuminate\Database\Eloquent\Collection;

interface CustomerAddressesInterface extends CrudInterface
{
    const PARAM_ID_ADDRESS   = CustomerAddress::FIELD_ID_ADDRESS;
    const PARAM_ID_CUSTOMER  = CustomerAddress::FIELD_ID_CUSTOMER;
    const PARAM_ADDRESS_TYPE = CustomerAddress::FIELD_TYPE;

    /**
     * Create customer address.
     *
     * @param array $input
     *
     * @return CustomerAddress
     */
    public function create(array $input);

    /**
     * Create customer address.
     *
     * @param Customer $customer
     * @param Address  $address
     * @param array    $attributes
     *
     * @return CustomerAddress
     */
    public function createAddress(Customer $customer, Address $address, array $attributes);

    /**
     * Read customer address by identifier.
     *
     * @param int $resourceId
     *
     * @return CustomerAddress
     */
    public function read($resourceId);

    /**
     * @param CustomerAddress $customerAddress
     *
     * @return void
     */
    public function deleteAddress(CustomerAddress $customerAddress);

    /**
     * Search customer addresses.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);

    /**
     * @param Customer $customer
     * @param Address  $address
     * @param string   $type
     * @param int      $quantity
     *
     * @return Collection
     */
    public function searchAddresses(Customer $customer = null, Address $address = null, $type = null, $quantity = null);

    /**
     * Set customer's address as default (for billing or shipping).
     *
     * @param CustomerAddress $customerAddress
     *
     * @return void
     */
    public function setAsDefault(CustomerAddress $customerAddress);
}
