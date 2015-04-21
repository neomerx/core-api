<?php namespace Neomerx\CoreApi\Api\Employees;

use \Neomerx\Core\Models\Employee;
use \Neomerx\Core\Models\EmployeeRole;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface EmployeeRolesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_ID          = EmployeeRole::FIELD_ID;
    /** Parameter key */
    const PARAM_ID_ROLE     = EmployeeRole::FIELD_ID_ROLE;
    /** Parameter key */
    const PARAM_ID_EMPLOYEE = EmployeeRole::FIELD_ID_EMPLOYEE;
    /** Parameter key */
    const PARAM_ROLE_CODE   = 'role_code';

    /**
     * Create employee role link.
     *
     * @param array $input
     *
     * @return EmployeeRole
     */
    public function create(array $input);

    /**
     * Read employee role link by identifier.
     *
     * @param int $idx
     *
     * @return EmployeeRole
     */
    public function read($idx);

    /**
     * Create employee role link.
     *
     * @param Employee $employee
     * @param array    $input
     *
     * @return EmployeeRole
     */
    public function createWithEmployee(Employee $employee, array $input);

    /**
     * Read employee role link by identifier.
     *
     * @param int $employeeId
     * @param int $employeeRoleId
     *
     * @return EmployeeRole
     */
    public function readWithEmployee($employeeId, $employeeRoleId);

    /**
     * Delete employee role link by identifier.
     *
     * @param int $employeeId
     * @param int $employeeRoleId
     *
     * @return void
     */
    public function deleteWithEmployee($employeeId, $employeeRoleId);

    /**
     * Search employee role links.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
