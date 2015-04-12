<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\SupplyOrder;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\Core\Models\SupplyOrderDetails as Model;
use \Neomerx\CoreApi\Api\SupplyOrders\SupplyOrderDetailsInterface;

/**
 * @see SupplyOrderDetailsInterface
 *
 * @method static Model      create(array $input)
 * @method static Model      createWithOrder(SupplyOrder $supplyOrder, array $input)
 * @method static Model      read(int $id)
 * @method static Model      readWithOrder(int $supplyOrderId, int $detailsId);
 * @method static void       update(array $input)
 * @method static void       delete(int $id)
 * @method static void       deleteWithOrder(int $supplyOrderId, int $detailsId);
 * @method static Collection search(array $parameters = [])
 */
class SupplyOrderDetails extends Facade
{
    const INTERFACE_BIND_NAME = SupplyOrderDetailsInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
