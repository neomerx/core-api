<?php namespace Neomerx\CoreApi\Api\Employees;

use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Employee;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\EmployeeRole;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Auth\RoleRepositoryInterface;
use \Neomerx\Core\Repositories\Employees\EmployeeRepositoryInterface;
use \Neomerx\Core\Repositories\Employees\EmployeeRoleRepositoryInterface;

/**
 * @package Neomerx\CoreApi
 */
class Employees extends SingleResourceApi implements EmployeesInterface
{
    /** Event prefix */
    const EVENT_PREFIX = 'Api.Employee.';

    /**
     * @var EmployeeRepositoryInterface
     */
    private $employeeRepo;

    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepo;

    /**
     * @var EmployeeRoleRepositoryInterface
     */
    private $employeeRoleRepo;

    /**
     * Constructor.
     *
     * @param EmployeeRepositoryInterface     $employeeRepo
     * @param EmployeeRoleRepositoryInterface $employeeRoleRepo
     * @param RoleRepositoryInterface         $roleRepo
     */
    public function __construct(
        EmployeeRepositoryInterface $employeeRepo,
        EmployeeRoleRepositoryInterface $employeeRoleRepo,
        RoleRepositoryInterface $roleRepo
    ) {
        $this->roleRepo         = $roleRepo;
        $this->employeeRepo     = $employeeRepo;
        $this->employeeRoleRepo = $employeeRoleRepo;
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
        return [
            Employee::withRoles(),
        ];
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
     */
    protected function onCreating(BaseModel $resource, array $input)
    {
        /** @var Employee $resource */

        parent::onCreating($resource, $input);

        $roleCodes  = S\arrayGetValue($input, self::PARAM_ROLES, []);
        foreach ($roleCodes as $roleCode) {
            /** @var Role $role */
            $role   = $this->readResourceFromRepository($roleCode, $this->roleRepo, [], [Role::FIELD_ID]);
            $this->employeeRoleRepo->instance($resource, $role)->saveOrFail();
        }
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
    protected function onUpdating(BaseModel $resource, array $input)
    {
        /** @var Employee $resource */

        parent::onUpdating($resource, $input);

        $roleCodes  = S\arrayGetValue($input, self::PARAM_ROLES, null);
        if ($roleCodes !== null && is_array($roleCodes) === true) {
            $employeeRoles = $this->employeeRoleRepo->search([], [
                EmployeeRole::FIELD_ID_EMPLOYEE => $resource->{Employee::FIELD_ID},
            ], null, [EmployeeRole::FIELD_ID]);

            // delete existing roles
            foreach ($employeeRoles as $employeeRole) {
                /** @var EmployeeRole $employeeRole */
                $employeeRole->deleteOrFail();
            }

            // add new roles
            foreach ($roleCodes as $roleCode) {
                /** @var Role $role */
                $role   = $this->readResourceFromRepository($roleCode, $this->roleRepo, [], [Role::FIELD_ID]);
                $this->employeeRoleRepo->instance($resource, $role)->saveOrFail();
            }
        }
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
