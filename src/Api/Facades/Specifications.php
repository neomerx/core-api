<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\Specification;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Products\SpecificationsInterface;

/**
 * @see SpecificationsInterface
 *
 * @method static Specification create(array $input)
 * @method static Specification read(string $code)
 * @method static void          update(string $code, array $input)
 * @method static void          delete(string $code)
 * @method static Collection    search()
 */
class Specifications extends Facade
{
    const INTERFACE_BIND_NAME = SpecificationsInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
