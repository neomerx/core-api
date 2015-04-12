<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\ProductCategory;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Products\ProductCategoriesInterface;

/**
 * @see ProductCategoriesInterface
 *
 * @method static ProductCategory create(array $input)
 * @method static ProductCategory read(string $code)
 * @method static void            update(string $code, array $input)
 * @method static void            delete(string $code)
 * @method static Collection      search()
 */
class ProductCategories extends Facade
{
    const INTERFACE_BIND_NAME = ProductCategoriesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
