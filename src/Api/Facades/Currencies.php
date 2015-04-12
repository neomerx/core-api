<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Currency;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Currencies\CurrenciesInterface;

/**
 * @see CurrenciesInterface
 *
 * @method static Currency   create(array $input)
 * @method static Currency   read(string $code)
 * @method static void       update(string $code, array $input)
 * @method static void       delete(string $code)
 * @method static Collection all()
 */
class Currencies extends Facade
{
    const INTERFACE_BIND_NAME = CurrenciesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
