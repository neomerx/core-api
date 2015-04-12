<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\ProductRelated;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Products\RelatedInterface;

/**
 * @see RelatedInterface
 *
 * @method static ProductRelated create(array $input)
 * @method static ProductRelated read(string $code)
 * @method static void           update(string $code, array $input)
 * @method static void           delete(string $code)
 * @method static Collection     search()
 */
class Related extends Facade
{
    const INTERFACE_BIND_NAME = RelatedInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
