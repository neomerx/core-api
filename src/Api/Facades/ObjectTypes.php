<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\ObjectType;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Auth\ObjectTypesInterface;

/**
 * @see ObjectTypesInterface
 *
 * @method static ObjectType create(array $input)
 * @method static ObjectType read(string $code)
 * @method static void       update(string $code, array $input)
 * @method static void       delete(string $code)
 * @method static Collection search(array $parameters = [])
 */
class ObjectTypes extends Facade
{
    const INTERFACE_BIND_NAME = ObjectTypesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
