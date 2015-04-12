<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\TaxRuleCustomerType;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleCustomerTypesInterface;

/**
 * @see TaxRuleCustomerTypesInterface
 *
 * @method static TaxRuleCustomerType create(array $input)
 * @method static TaxRuleCustomerType read(string $code)
 * @method static void                update(string $code, array $input)
 * @method static void                delete(string $code)
 * @method static Collection          search(array $parameters = [])
 */
class TaxRuleCustomerTypes extends Facade
{
    const INTERFACE_BIND_NAME = TaxRuleCustomerTypesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
