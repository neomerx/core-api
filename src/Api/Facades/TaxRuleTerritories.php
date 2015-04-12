<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\TaxRuleTerritory;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleTerritoriesInterface;

/**
 * @see TaxRuleTerritoriesInterface
 *
 * @method static TaxRuleTerritory create(array $input)
 * @method static TaxRuleTerritory read(string $code)
 * @method static void             update(string $code, array $input)
 * @method static void             delete(string $code)
 * @method static Collection       search(array $parameters = [])
 */
class TaxRuleTerritories extends Facade
{
    const INTERFACE_BIND_NAME = TaxRuleTerritoriesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
