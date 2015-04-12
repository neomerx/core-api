<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\CustomerType;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Customers\CustomerTypesInterface;

/**
 * @see CustomerTypesInterface
 *
 * @method static CustomerType create(array $input)
 * @method static CustomerType read(string $code)
 * @method static void         update(string $code, array $input)
 * @method static void         delete(string $code)
 * @method static Collection   search()
 */
final class CustomerTypes extends Facade
{
    const INTERFACE_BIND_NAME = CustomerTypesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
