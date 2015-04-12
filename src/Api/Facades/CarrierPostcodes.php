<?php namespace Neomerx\CoreApi\Api\Facades;

use \Illuminate\Support\Facades\Facade;
use \Neomerx\Core\Models\CarrierPostcode;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Carriers\CarrierPostcodesInterface;

/**
 * @see CarrierPostcodesInterface
 *
 * @method static CarrierPostcode create(array $input)
 * @method static CarrierPostcode read(int $idx)
 * @method static void            update(int $idx, array $input)
 * @method static void            delete(int $idx)
 * @method static Collection      search(array $parameters = [])
 */
class CarrierPostcodes extends Facade
{
    const INTERFACE_BIND_NAME = CarrierPostcodesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
