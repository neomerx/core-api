<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\CarrierCustomerType;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Carriers\CarrierCustomerTypesInterface;

/**
 * @see CarrierCustomerTypesInterface
 *
 * @method static CarrierCustomerType create(array $input)
 * @method static CarrierCustomerType read(int $idx)
 * @method static void                update(int $idx, array $input)
 * @method static void                delete(int $idx)
 * @method static Collection          search(array $parameters = [])
 */
class CarrierCustomerTypes extends Facade
{
    const INTERFACE_BIND_NAME = CarrierCustomerTypesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
