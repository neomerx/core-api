<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Address;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Addresses\AddressesInterface;

/**
 * @see AddressesInterface
 *
 * @method static Address    create(array $input)
 * @method static Address    read(string $code)
 * @method static void       update(string $code, array $input)
 * @method static void       delete(string $code)
 * @method static void       updateModel(Address $address, array $input)
 * @method static void       deleteModel(Address $address)
 * @method static Collection search(array $parameters = [])
 */
class Addresses extends Facade
{
    /** Interface the facade is bind to */
    const INTERFACE_BIND_NAME = AddressesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
