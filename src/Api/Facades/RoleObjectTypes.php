<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\Core\Models\ObjectType as Model;
use \Neomerx\CoreApi\Api\Auth\RoleObjectTypesInterface;

/**
 * @see ObjectTypesInterface
 *
 * @method static Model      create(array $input)
 * @method static Model      read(int $id)
 * @method static void       update(array $input)
 * @method static void       delete(int $id)
 * @method static Collection search(array $parameters = [])
 */
class RoleObjectTypes extends Facade
{
    /** Interface the facade is bind to */
    const INTERFACE_BIND_NAME = RoleObjectTypesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
