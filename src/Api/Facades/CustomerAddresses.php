<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Address;
use \Neomerx\Core\Models\Customer;
use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\CustomerAddress;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Customers\CustomerAddressesInterface;

/**
 * @see CustomerAddressesInterface
 *
 * @method static CustomerAddress create(array $input)
 * @method static CustomerAddress createAddress(Customer $customer, Address $address, array $attributes)
 * @method static CustomerAddress read(int $resourceId)
 * @method static void            update(array $input)
 * @method static void            delete(int $resourceId)
 * @method static void            setAsDefault(CustomerAddress $customerAddress)
 * @method static Collection      search(array $parameters)
 *
 * @codingStandardsIgnoreStart
 * @method static Collection searchAddresses(Customer $customer = null, Address $address = null, string $type = null, int $quantity = null)
 * @codingStandardsIgnoreEnd
 */
final class CustomerAddresses extends Facade
{
    const INTERFACE_BIND_NAME = CustomerAddressesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
