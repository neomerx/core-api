<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Warehouse;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Warehouses\WarehousesInterface;

/**
 * @see WarehousesInterface
 *
 * @method static Warehouse  create(array $input)
 * @method static Warehouse  read(string $code)
 * @method static void       update(string $code, array $input)
 * @method static void       delete(string $code)
 * @method static Collection search(array $parameters = [])
 * @method static Warehouse  getDefault()
 */
class Warehouses extends Facade
{
    const INTERFACE_BIND_NAME = WarehousesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
