<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\CharacteristicValue;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Features\FeatureValuesInterface;

/**
 * @see FeatureValuesInterface
 *
 * @method static CharacteristicValue create(array $input)
 * @method static CharacteristicValue read(string $code)
 * @method static void                update(string $code, array $input)
 * @method static void                delete(string $code)
 * @method static Collection          search(array $parameters)
 */
class FeatureValues extends Facade
{
    const INTERFACE_BIND_NAME = FeatureValuesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
