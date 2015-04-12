<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Role;
use \Illuminate\Support\Facades\Facade;
use \Neomerx\CoreApi\Api\Auth\RolesInterface;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @see RolesInterface
 *
 * @method static Role       create(array $input)
 * @method static Role       read(string $code)
 * @method static void       update(string $code, array $input)
 * @method static void       delete(string $code)
 * @method static Collection search(array $parameters = [])
 */
class Roles extends Facade
{
    const INTERFACE_BIND_NAME = RolesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
