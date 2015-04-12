<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\ProductTaxType;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Products\ProductTaxTypesInterface;

/**
 * @see ProductTaxTypesInterface
 *
 * @method static ProductTaxType create(array $input)
 * @method static ProductTaxType read(string $code)
 * @method static void           update(string $code, array $input)
 * @method static void           delete(string $code)
 * @method static Collection     search()
 */
class ProductTaxTypes extends Facade
{
    const INTERFACE_BIND_NAME = ProductTaxTypesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
