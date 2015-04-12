<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Neomerx\CoreApi\Api\Facades\EmployeeRoles;
use \Neomerx\CoreApi\Api\Employees\EmployeeRolesInterface;
use \Neomerx\CoreApi\Converters\EmployeeRoleConverterGeneric;

final class EmployeeRolesControllerJson extends BaseDependentResourceControllerJson
{
    /**
     * @var EmployeeRolesInterface
     */
    private $apiFacade;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            app(EmployeeRoleConverterGeneric::BIND_NAME),
            EmployeeRolesInterface::PARAM_ID_EMPLOYEE,
            EmployeeRolesInterface::PARAM_ID
        );
        $this->apiFacade = app(EmployeeRoles::INTERFACE_BIND_NAME);
    }

    /**
     * @inheritdoc
     */
    protected function apiSearch(array $parameters)
    {
        return $this->apiFacade->search($parameters);
    }

    /**
     * @inheritdoc
     */
    protected function apiCreate(array $input)
    {
        return $this->apiFacade->create($input);
    }

    /**
     * @inheritdoc
     */
    protected function apiRead($parentId, $resourceId)
    {
        return $this->apiFacade->readWithEmployee($parentId, $resourceId);
    }

    /**
     * @inheritdoc
     */
    protected function apiUpdate($resourceId, array $input)
    {
        $this->apiFacade->update($resourceId, $input);
    }

    /**
     * @inheritdoc
     */
    protected function apiDelete($parentId, $resourceId)
    {
        $this->apiFacade->deleteWithEmployee($parentId, $resourceId);
    }
}
