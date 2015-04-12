<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Variant;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Products\VariantsInterface;

/**
 * @see VariantsInterface
 *
 * @method static Variant    create(array $input)
 * @method static Variant    read(string $sku)
 * @method static void       update(string $sku, array $input)
 * @method static void       delete(string $sku)
 * @method static Collection search(array $parameters = [])
 */
class Variants extends Facade
{
    const INTERFACE_BIND_NAME = VariantsInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
