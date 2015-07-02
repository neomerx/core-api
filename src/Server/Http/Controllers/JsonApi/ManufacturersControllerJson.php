<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Schemas\AddressSchema;
use \Neomerx\CoreApi\Api\Facades\Manufacturers;
use \Neomerx\CoreApi\Schemas\ManufacturerSchema;
use \Neomerx\CoreApi\Api\Manufacturers\ManufacturersInterface;

/**
 * @package Neomerx\CoreApi
 */
final class ManufacturersControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Manufacturers::INTERFACE_BIND_NAME);
    }

    /**
     * Create a manufacturer.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, $manufacturerCode, $attributes, $relationships) =
            $this->parseDocumentAsSingleData(ManufacturerSchema::TYPE, [
                ManufacturerSchema::REL_ADDRESS => AddressSchema::TYPE,
            ]);

        $properties = S\arrayGetValueEx($attributes, ManufacturerSchema::ATTR_PROPERTIES);
        $resource = $this->getApiFacade()->create([
            ManufacturersInterface::PARAM_CODE       => $manufacturerCode,
            ManufacturersInterface::PARAM_ID_ADDRESS => $relationships[ManufacturerSchema::REL_ADDRESS],
            ManufacturersInterface::PARAM_PROPERTIES => $properties,
        ]);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update manufacturer.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function update($resourceCode)
    {
        $this->checkParametersEmpty();

        list(, , $attributes, $relationships) =
            $this->parseDocumentAsSingleData(ManufacturerSchema::TYPE, [
                ManufacturerSchema::REL_ADDRESS => AddressSchema::TYPE,
            ]);

        $addressId  = S\arrayGetValue($relationships, ManufacturerSchema::REL_ADDRESS);
        $properties = S\arrayGetValue($attributes, ManufacturerSchema::ATTR_PROPERTIES);

        $input = S\arrayFilterNulls([
            ManufacturersInterface::PARAM_ID_ADDRESS => $addressId,
            ManufacturersInterface::PARAM_PROPERTIES => $properties,
        ]);
        $this->getApiFacade()->update($resourceCode, $input);

        return $this->getContentResponse($this->getApiFacade()->read($resourceCode));
    }
}
