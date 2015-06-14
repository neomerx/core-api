<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Schemas\RegionSchema;
use \Neomerx\CoreApi\Api\Facades\Addresses;
use \Neomerx\CoreApi\Schemas\AddressSchema;
use \Neomerx\CoreApi\Api\Addresses\AddressesInterface;

/**
 * @package Neomerx\CoreApi
 */
final class AddressesControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Addresses::INTERFACE_BIND_NAME);
    }

    /**
     * Create a region.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, , $attributes, $relationships) = $this->parseDocumentAsSingleData(AddressSchema::TYPE, [
                AddressSchema::REL_REGION => RegionSchema::TYPE,
            ]);

        $attributes[AddressesInterface::PARAM_REGION_CODE] = $relationships[AddressSchema::REL_REGION];

        $resource = $this->getApiFacade()->create($attributes);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update region.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function update($resourceCode)
    {
        $this->checkParametersEmpty();

        list(, , $attributes, $relationships) = $this->parseDocumentAsSingleData(AddressSchema::TYPE, [
            AddressSchema::REL_REGION => RegionSchema::TYPE,
        ]);

        $attributes[AddressesInterface::PARAM_REGION_CODE] = S\arrayGetValue($relationships, AddressSchema::REL_REGION);

        $input = S\arrayFilterNulls($attributes);
        $this->getApiFacade()->update($resourceCode, $input);

        return $this->getContentResponse($this->getApiFacade()->read($resourceCode));
    }
}
