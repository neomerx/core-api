<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\SupplyOrder;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\SupplyOrders\SupplyOrdersInterface;

/**
 * @see SupplyOrdersInterface
 *
 * @method static SupplyOrder create(array $input)
 * @method static SupplyOrder read(int $id)
 * @method static void        update(array $input)
 * @method static void        delete(int $id)
 * @method static Collection  search(array $parameters = [])
 */
class SupplyOrders extends Facade
{
    const INTERFACE_BIND_NAME = SupplyOrdersInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
