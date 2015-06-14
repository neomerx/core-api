<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Schemas\AddressSchema;
use \Neomerx\CoreApi\Api\Facades\Suppliers;
use \Neomerx\CoreApi\Schemas\SupplierSchema;
use \Neomerx\CoreApi\Api\Suppliers\SuppliersInterface;

/**
 * @package Neomerx\CoreApi
 */
final class SuppliersControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Suppliers::INTERFACE_BIND_NAME);
    }

    /**
     * Create a supplier.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, $supplierCode, $attributes, $relationships) =
            $this->parseDocumentAsSingleData(SupplierSchema::TYPE, [
                SupplierSchema::REL_ADDRESS => AddressSchema::TYPE,
            ]);

        $properties = S\arrayGetValueEx($attributes, SupplierSchema::ATTR_PROPERTIES);
        $resource = $this->getApiFacade()->create([
            SuppliersInterface::PARAM_CODE       => $supplierCode,
            SuppliersInterface::PARAM_ID_ADDRESS => $relationships[SupplierSchema::REL_ADDRESS],
            SuppliersInterface::PARAM_PROPERTIES => $properties,
        ]);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update supplier.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function update($resourceCode)
    {
        $this->checkParametersEmpty();

        list(, , $attributes, $relationships) =
            $this->parseDocumentAsSingleData(SupplierSchema::TYPE, [
                SupplierSchema::REL_ADDRESS => AddressSchema::TYPE,
            ]);

        $addressId  = S\arrayGetValue($relationships, SupplierSchema::REL_ADDRESS);
        $properties = S\arrayGetValueEx($attributes, SupplierSchema::ATTR_PROPERTIES);

        $input = S\arrayFilterNulls([
            SuppliersInterface::PARAM_ID_ADDRESS => $addressId,
            SuppliersInterface::PARAM_PROPERTIES => $properties,
        ]);
        $this->getApiFacade()->update($resourceCode, $input);

        return $this->getContentResponse($this->getApiFacade()->read($resourceCode));
    }
}
