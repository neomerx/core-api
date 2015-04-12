<?php namespace Neomerx\CoreApi\Api\Employees;

use \Neomerx\Core\Models\Employee;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Illuminate\Database\Eloquent\Collection;

interface EmployeesInterface extends CrudInterface
{
    const PARAM_ID                    = Employee::FIELD_ID;
    const PARAM_FIRST_NAME            = Employee::FIELD_FIRST_NAME;
    const PARAM_LAST_NAME             = Employee::FIELD_LAST_NAME;
    const PARAM_EMAIL                 = Employee::FIELD_EMAIL;
    const PARAM_ACTIVE                = Employee::FIELD_ACTIVE;
    const PARAM_PASSWORD              = Employee::FIELD_PASSWORD;
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
