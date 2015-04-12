<?php namespace Neomerx\CoreApi\Api\Employees;

use \Neomerx\Core\Models\Employee;
use \Neomerx\CoreApi\Events\EventArgs;

class EmployeeArgs extends EventArgs
{
    /**
     * @var Employee
     */
    private $employee;

    /**
     * @param string    $name
     * @param Employee  $employee
     * @param EventArgs $args
     */
    public function __construct($name, Employee $employee, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->employee = $employee;
    }

    /**
     * @return Employee
     */
    public function getModel()
    {
        return $this->employee;
    }
}
