<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\Core\Models\ShippingOrderStatus;
use \Neomerx\CoreApi\Api\ShippingOrders\ShippingStatusesInterface;

/**
 * @see ShippingStatusesInterface
 *
 * @method static ShippingOrderStatus create(array $input)
 * @method static ShippingOrderStatus read(string $code)
 * @method static void                update(string $code, array $input)
 * @method static void                delete(string $code)
 * @method static Collection          search(array $parameters = [])
 */
class ShippingStatuses extends Facade
{
    const INTERFACE_BIND_NAME = ShippingStatusesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
