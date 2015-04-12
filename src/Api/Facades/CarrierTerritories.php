<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\CarrierTerritory;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Carriers\CarrierTerritoriesInterface;

/**
 * @see CarrierTerritorysInterface
 *
 * @method static CarrierTerritory create(array $input)
 * @method static CarrierTerritory read(int $idx)
 * @method static void             update(int $idx, array $input)
 * @method static void             delete(int $idx)
 * @method static Collection       search(array $parameters = [])
 */
class CarrierTerritories extends Facade
{
    const INTERFACE_BIND_NAME = CarrierTerritoriesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
