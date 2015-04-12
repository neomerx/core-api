<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\ProductImage;
use \Illuminate\Support\Facades\Facade;
use \Neomerx\CoreApi\Api\Products\ProductImagesInterface;

/**
 * @see ProductImagesInterface
 *
 * @method static ProductImage create(array $input)
 * @method static ProductImage read(string $code)
 * @method static void         update(string $code, array $input)
 * @method static void         delete(string $code)
 */
class ProductImages extends Facade
{
    const INTERFACE_BIND_NAME = ProductImagesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
