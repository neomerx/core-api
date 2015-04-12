<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\TaxRuleProductType;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleProductTypesInterface;

/**
 * @see TaxRuleProductTypesInterface
 *
 * @method static TaxRuleProductType create(array $input)
 * @method static TaxRuleProductType read(string $code)
 * @method static void               update(string $code, array $input)
 * @method static void               delete(string $code)
 * @method static Collection         search(array $parameters = [])
 */
class TaxRuleProductTypes extends Facade
{
    const INTERFACE_BIND_NAME = TaxRuleProductTypesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
