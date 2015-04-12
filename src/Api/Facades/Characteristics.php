<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Characteristic;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Features\CharacteristicsInterface;

/**
 * @see CharacteristicsInterface
 *
 * @method static Characteristic create(array $input)
 * @method static Characteristic read(string $code)
 * @method static void           update(string $code, array $input)
 * @method static void           delete(string $code)
 * @method static Collection     search(array $parameters)
 * @method static Collection     getValues(string $characteristicCode)
 */
class Characteristics extends Facade
{
    const INTERFACE_BIND_NAME = CharacteristicsInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
