<?php namespace Neomerx\CoreApi\Api\Employees;

use \Neomerx\Core\Models\Employee;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface EmployeesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_ID                    = Employee::FIELD_ID;
    /** Parameter key */
    const PARAM_FIRST_NAME            = Employee::FIELD_FIRST_NAME;
    /** Parameter key */
    const PARAM_LAST_NAME             = Employee::FIELD_LAST_NAME;
    /** Parameter key */
    const PARAM_EMAIL                 = Employee::FIELD_EMAIL;
    /** Parameter key */
    const PARAM_ACTIVE                = Employee::FIELD_ACTIVE;
    /** Parameter key */
    const PARAM_ROLES                 = Employee::FIELD_ROLES;
    /** Parameter key */
    const PARAM_PASSWORD              = Employee::FIELD_PASSWORD;
    /** Parameter key */
    const PARAM_PASSWORD_CONFIRMATION = Employee::PARAM_PASSWORD_CONFIRMATION;

    /**
     * Create employee.
     *
     * @param array $input
     *
     * @return Employee
     */
    public function create(array $input);

    /**
     * Read employee by identifier.
     *
     * @param int $idx
     *
     * @return Employee
     */
    public function read($idx);

    /**
     * Search employees.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
