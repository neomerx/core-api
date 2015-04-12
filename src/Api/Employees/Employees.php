<?php namespace Neomerx\CoreApi\Api\Employees;

use \Neomerx\Core\Models\Employee;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Employees\EmployeeRepositoryInterface;

class Employees extends SingleResourceApi implements EmployeesInterface
{
    const EVENT_PREFIX = 'Api.Employee.';
    const BIND_NAME = __CLASS__;

    /**
     * @var EmployeeRepositoryInterface
     */
    private $employeeRepo;

    /**
     * Constructor.
     *
     * @param EmployeeRepositoryInterface $employeeRepo
     */
    public function __construct(EmployeeRepositoryInterface $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Employee::FIELD_ID         => SearchGrammar::TYPE_STRING,
            Employee::FIELD_FIRST_NAME => SearchGrammar::TYPE_STRING,
            Employee::FIELD_LAST_NAME  => SearchGrammar::TYPE_STRING,
            Employee::FIELD_EMAIL      => SearchGrammar::TYPE_STRING,
            Employee::FIELD_ACTIVE     => SearchGrammar::TYPE_BOOL,
            SearchGrammar::LIMIT_SKIP  => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE  => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->employeeRepo;
    }

    /**
     * @inheritdoc
     * @return Employee
     */
    protected function getInstance(array $input)
    {
        return $this->employeeRepo->instance($input);
    }

    /**
     * @inheritdoc
     * @return Employee
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Employee $resource */
        $this->employeeRepo->fill($resource, $input);

        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Employee $resource */
        return new EmployeeArgs(self::EVENT_PREFIX . $eventNamePostfix, $resource);
    }
}
