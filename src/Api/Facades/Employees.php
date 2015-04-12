<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Employee;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Employees\EmployeesInterface;

/**
 * @see EmployeesInterface
 *
 * @method static Employee   create(array $input)
 * @method static Employee   read(string $code)
 * @method static void       update(string $code, array $input)
 * @method static void       delete(string $code)
 * @method static Collection search(array $parameters = [])
 */
class Employees extends Facade
{
    const INTERFACE_BIND_NAME = EmployeesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
