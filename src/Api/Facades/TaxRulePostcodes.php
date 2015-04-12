<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\TaxRulePostcode;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Taxes\TaxRulePostcodesInterface;

/**
 * @see TaxRulePostcodesInterface
 *
 * @method static TaxRulePostcode create(array $input)
 * @method static TaxRulePostcode read(string $code)
 * @method static void            update(string $code, array $input)
 * @method static void            delete(string $code)
 * @method static Collection      search(array $parameters = [])
 */
class TaxRulePostcodes extends Facade
{
    const INTERFACE_BIND_NAME = TaxRulePostcodesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
