<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Api\Facades\ObjectTypes;
use \Neomerx\CoreApi\Schemas\ObjectTypeSchema;
use \Neomerx\CoreApi\Api\Auth\ObjectTypesInterface;

/**
 * @package Neomerx\CoreApi
 */
final class ObjectTypesControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(ObjectTypes::INTERFACE_BIND_NAME);
    }

    /**
     * Create an object type.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, , $attributes) = $this->parseDocumentAsSingleData(ObjectTypeSchema::TYPE);

        $attributes = $this->convertAttributes($attributes);

        $resource = $this->getApiFacade()->create($attributes);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update object type.
     *
     * @param string $objectTypeId
     *
     * @return Response
     */
    final public function update($objectTypeId)
    {
        $this->checkParametersEmpty();

        list(, , $attributes) = $this->parseDocumentAsSingleData(ObjectTypeSchema::TYPE);

        $attributes = $this->convertAttributes($attributes);

        $this->getApiFacade()->update($objectTypeId, $attributes);

        return $this->getContentResponse($this->getApiFacade()->read($objectTypeId));
    }

    /**
     * @param $attributes
     *
     * @return mixed
     */
    private function convertAttributes($attributes)
    {
        $objectType = S\arrayGetValueEx($attributes, ObjectTypeSchema::ATTR_OBJECT_TYPE);
        $attributes[ObjectTypesInterface::PARAM_TYPE] = $objectType;
        unset($attributes[ObjectTypeSchema::ATTR_OBJECT_TYPE]);

        return $attributes;
    }
}
