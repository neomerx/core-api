<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Api\Facades\Roles;
use \Neomerx\CoreApi\Schemas\RoleSchema;
use \Neomerx\CoreApi\Api\Auth\RolesInterface;

/**
 * @package Neomerx\CoreApi
 */
final class RolesControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Roles::INTERFACE_BIND_NAME);
    }

    /**
     * Create a role.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, $roleCode, $attributes) = $this->parseDocumentAsSingleData(RoleSchema::TYPE);

        // language code is an attribute on database level so add it
        $attributes[RolesInterface::PARAM_CODE] = $roleCode;

        $resource = $this->getApiFacade()->create($attributes);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update role.
     *
     * @param string $roleCode
     *
     * @return Response
     */
    final public function update($roleCode)
    {
        $this->checkParametersEmpty();

        list(, , $attributes) = $this->parseDocumentAsSingleData(RoleSchema::TYPE);

        $this->getApiFacade()->update($roleCode, $attributes);

        return $this->getContentResponse($this->getApiFacade()->read($roleCode));
    }
}
