<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Carrier;
use \Neomerx\CoreApi\Api\Carriers\Tariff;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Carriers\ShippingData;
use \Neomerx\CoreApi\Api\Carriers\CarriersInterface;

/**
 * @see CarriersInterface
 *
 * @method static Carrier    create(array $input)
 * @method static Carrier    read(string $code)
 * @method static void       update(string $code, array $input)
 * @method static void       delete(string $code)
 * @method static Collection search(array $parameters = [])
 * @method static array      calculateTariffs(ShippingData $shippingData)
 * @method static Tariff     calculateTariff(ShippingData $shippingData, Carrier $carrier)
 */
class Carriers extends Facade
{
    const INTERFACE_BIND_NAME = CarriersInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
