<?php namespace Neomerx\CoreApi\Api\Employees;

use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Models\Employee;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\EmployeeRole;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\CoreApi\Api\DependentSingleResourceApi;
use \Neomerx\Core\Repositories\Auth\RoleRepositoryInterface;
use \Neomerx\Core\Repositories\Employees\EmployeeRepositoryInterface;
use \Neomerx\Core\Repositories\Employees\EmployeeRoleRepositoryInterface;

class EmployeeRoles extends DependentSingleResourceApi implements EmployeeRolesInterface
{
    const EVENT_PREFIX = 'Api.EmployeeRole.';

    /**
     * @var EmployeeRoleRepositoryInterface
     */
    private $employeeRoleRepo;

    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepo;

    /**
     * @var EmployeeRepositoryInterface
     */
    private $employeeRepo;

    /**
     * @param EmployeeRoleRepositoryInterface $employeeRoleRepo
     * @param RoleRepositoryInterface         $roleRepo
     * @param EmployeeRepositoryInterface     $employeeRepo
     */
    public function __construct(
        EmployeeRoleRepositoryInterface $employeeRoleRepo,
        RoleRepositoryInterface $roleRepo,
        EmployeeRepositoryInterface $employeeRepo
    ) {
        parent::__construct($employeeRepo, self::PARAM_ID_EMPLOYEE, self::PARAM_ID);

        $this->roleRepo         = $roleRepo;
        $this->employeeRepo     = $employeeRepo;
        $this->employeeRoleRepo = $employeeRoleRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->employeeRoleRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            EmployeeRole::withRole(),
            EmployeeRole::withEmployee(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            EmployeeRole::FIELD_ID          => SearchGrammar::TYPE_INT,
            EmployeeRole::FIELD_ID_ROLE     => SearchGrammar::TYPE_INT,
            EmployeeRole::FIELD_ID_EMPLOYEE => SearchGrammar::TYPE_INT,
            SearchGrammar::LIMIT_SKIP       => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE       => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var EmployeeRole $resource */
        return new EmployeeRoleArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function instanceWithParent(BaseModel $parentResource, array $input)
    {
        assert('$parentResource instanceof '.Employee::class);

        /** @var Employee $parentResource */

        /** @var Role $role */
        $role = $this->keyToModelEx($input, self::PARAM_ROLE_CODE, $this->roleRepo);

        $resource = $this->employeeRoleRepo->instance($parentResource, $role);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var EmployeeRole $resource */

        /** @var Role $role */
        $role = $this->keyToModel($input, self::PARAM_ROLE_CODE, $this->roleRepo);

        /** @var Employee $employee */
        $employee = $this->keyToModel($input, self::PARAM_ID_EMPLOYEE, $this->employeeRepo);

        $this->employeeRoleRepo->fill($resource, $employee, $role);
        return $resource;
    }

    /**
     * @inheritdoc
     */
    public function createWithEmployee(Employee $employee, array $input)
    {
        return $this->createWith($employee, $input);
    }

    /**
     * @inheritdoc
     */
    public function readWithEmployee($employeeId, $employeeRoleId)
    {
        return $this->readWith($employeeId, $employeeRoleId);
    }

    /**
     * @inheritdoc
     */
    public function deleteWithEmployee($employeeId, $employeeRoleId)
    {
        $this->deleteWith($employeeId, $employeeRoleId);
    }
}
