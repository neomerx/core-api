<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Store;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Stores\StoresInterface;

/**
 * @see StoresInterface
 *
 * @method static Store      create(array $input)
 * @method static Store      read(string $code)
 * @method static void       update(string $code, array $input)
 * @method static void       delete(string $code)
 * @method static Collection search(array $parameters = [])
 * @method static Store      getDefault()
 */
class Stores extends Facade
{
    const INTERFACE_BIND_NAME = StoresInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
