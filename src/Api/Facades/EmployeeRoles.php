<?php namespace Neomerx\CoreApi\Api\Facades;

use \Neomerx\Core\Models\Employee;
use \Illuminate\Support\Facades\Facade;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\Core\Models\EmployeeRole as Model;
use \Neomerx\CoreApi\Api\Employees\EmployeeRolesInterface;

/**
 * @see EmployeeRolesInterface
 *
 * @method static Model      create(array $input)
 * @method static Model      createWithEmployee(Employee $employee, array $input)
 * @method static Model      read(int $id)
 * @method static Model      readWithEmployee(int $employeeId, int $roleId);
 * @method static void       update(array $input)
 * @method static void       delete(int $id)
 * @method static void       deleteWithEmployee(int $employeeId, int $roleId);
 * @method static Collection search(array $parameters = [])
 */
class EmployeeRoles extends Facade
{
    const INTERFACE_BIND_NAME = EmployeeRolesInterface::class;

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return self::INTERFACE_BIND_NAME;
    }
}
