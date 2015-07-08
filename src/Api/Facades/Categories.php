<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Category;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Categories\CategoriesInterface;

/**
 * @see CategoriesInterface
 *
 * @method static Category   create(array $input)
 * @method static Category   read(string $code)
 * @method static void       update(string $code, array $input)
 * @method static void       delete(string $code)
 * @method static Collection readDescendants(string $parentCode)
 * @method static void       moveUp(string $categoryCode)
 * @method static void       moveDown(string $categoryCode)
 * @method static void       attach(string $categoryCode, string $newParentCode)
 */
class Categories extends Facade
{
    /** Interface the facade is bind to */
    const INTERFACE_BIND_NAME = CategoriesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
