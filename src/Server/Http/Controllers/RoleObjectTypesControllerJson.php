<?php namespace Neomerx\CoreApi\Server\Http\Controllers;

use \Neomerx\CoreApi\Api\Facades\RoleObjectTypes;
use \Neomerx\CoreApi\Api\Auth\RoleObjectTypesInterface;
use \Neomerx\CoreApi\Converters\RoleObjectTypeConverterGeneric;

final class RoleObjectTypesControllerJson extends BaseDependentResourceControllerJson
{
    /**
     * @var RoleObjectTypesInterface
     */
    private $apiFacade;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            app(RoleObjectTypeConverterGeneric::BIND_NAME),
            RoleObjectTypesInterface::PARAM_ROLE_CODE,
            RoleObjectTypesInterface::PARAM_ID
        );
        $this->apiFacade = app(RoleObjectTypes::INTERFACE_BIND_NAME);
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
        return $this->apiFacade->readWithRole($parentId, $resourceId);
    }

    /**
     * @inheritdoc
     */
    protected function apiUpdate($resourceId, array $input)
    {
        return $this->apiFacade->update($resourceId, $input);
    }

    /**
     * @inheritdoc
     */
    protected function apiDelete($parentId, $resourceId)
    {
        $this->apiFacade->deleteWithRole($parentId, $resourceId);
    }
}
