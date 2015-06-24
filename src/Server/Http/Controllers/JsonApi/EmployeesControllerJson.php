<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Api\Facades\Employees;
use \Neomerx\CoreApi\Schemas\EmployeeSchema;
use \Neomerx\CoreApi\Api\Employees\EmployeesInterface;

/**
 * @package Neomerx\CoreApi
 */
final class EmployeesControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Employees::INTERFACE_BIND_NAME);
    }

    /**
     * Create a employee.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, , $attributes) = $this->parseDocumentAsSingleData(EmployeeSchema::TYPE);

        $attributes[EmployeesInterface::PARAM_PASSWORD_CONFIRMATION] =
            S\arrayGetValueEx($attributes, EmployeeSchema::ATTR_PASSWORD);

        $resource = $this->getApiFacade()->create($attributes);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update employee.
     *
     * @param string $employeeId
     *
     * @return Response
     */
    final public function update($employeeId)
    {
        $this->checkParametersEmpty();

        list(, , $attributes) = $this->parseDocumentAsSingleData(EmployeeSchema::TYPE);

        $attributes[EmployeesInterface::PARAM_PASSWORD_CONFIRMATION] =
            S\arrayGetValue($attributes, EmployeeSchema::ATTR_PASSWORD);

        $input = S\arrayFilterNulls($attributes);
        $this->getApiFacade()->update($employeeId, $input);

        return $this->getContentResponse($this->getApiFacade()->read($employeeId));
    }
}
