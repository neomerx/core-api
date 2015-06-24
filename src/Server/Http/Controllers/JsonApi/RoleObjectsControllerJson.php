<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Schemas\RoleSchema;
use \Neomerx\CoreApi\Schemas\ObjectTypeSchema;
use \Neomerx\CoreApi\Schemas\RoleObjectSchema;
use \Neomerx\CoreApi\Api\Facades\RoleObjectTypes;
use \Neomerx\CoreApi\Api\Auth\RoleObjectTypesInterface;

/**
 * @package Neomerx\CoreApi
 */
final class RoleObjectsControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(RoleObjectTypes::INTERFACE_BIND_NAME);
    }

    /**
     * Create a role object.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, , $attributes, $relationships) = $this->parseDocumentAsSingleData(RoleObjectSchema::TYPE, [
                RoleObjectSchema::REL_ROLE        => RoleSchema::TYPE,
                RoleObjectSchema::REL_OBJECT_TYPE => ObjectTypeSchema::TYPE,
            ]);

        $attributes[RoleObjectTypesInterface::PARAM_ROLE_CODE] = $relationships[RoleObjectSchema::REL_ROLE];
        $attributes[RoleObjectTypesInterface::PARAM_ID_TYPE]   = $relationships[RoleObjectSchema::REL_OBJECT_TYPE];

        $resource = $this->getApiFacade()->create($attributes);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update role object.
     *
     * @param string $resourceId
     *
     * @return Response
     */
    final public function update($resourceId)
    {
        $this->checkParametersEmpty();

        list(, , $attributes, $relationships) = $this->parseDocumentAsSingleData(RoleObjectSchema::TYPE, [
            RoleObjectSchema::REL_ROLE        => RoleSchema::TYPE,
            RoleObjectSchema::REL_OBJECT_TYPE => ObjectTypeSchema::TYPE,
        ]);

        $attributes[RoleObjectTypesInterface::PARAM_ROLE_CODE] =
            S\arrayGetValue($relationships, RoleObjectSchema::REL_ROLE);
        $attributes[RoleObjectTypesInterface::PARAM_ID_TYPE] =
            S\arrayGetValue($relationships, RoleObjectSchema::REL_OBJECT_TYPE);


        $input = S\arrayFilterNulls($attributes);
        $this->getApiFacade()->update($resourceId, $input);

        return $this->getContentResponse($this->getApiFacade()->read($resourceId));
    }
}
