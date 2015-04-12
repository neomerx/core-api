<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\CustomerRisk;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Customers\CustomerRisksInterface;

/**
 * @see CustomerRisksInterface
 *
 * @method static CustomerRisk create(array $input)
 * @method static CustomerRisk read(string $code)
 * @method static void         update(string $code, array $input)
 * @method static void         delete(string $code)
 * @method static Collection   search()
 */
final class CustomerRisks extends Facade
{
    const INTERFACE_BIND_NAME = CustomerRisksInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
