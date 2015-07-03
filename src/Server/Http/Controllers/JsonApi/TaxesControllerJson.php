<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\CoreApi\Api\Facades\Taxes;
use \Neomerx\CoreApi\Schemas\TaxSchema;
use \Neomerx\CoreApi\Api\Taxes\TaxesInterface;

/**
 * @package Neomerx\CoreApi
 */
final class TaxesControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Taxes::INTERFACE_BIND_NAME);
    }

    /**
     * Create a tax.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, $taxCode, $attributes) = $this->parseDocumentAsSingleData(TaxSchema::TYPE);

        // tax code is an attribute on database level so add it
        $attributes[TaxesInterface::PARAM_CODE] = $taxCode;

        $resource = $this->getApiFacade()->create($attributes);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update tax.
     *
     * @param string $taxCode
     *
     * @return Response
     */
    final public function update($taxCode)
    {
        $this->checkParametersEmpty();

        list(, , $attributes) = $this->parseDocumentAsSingleData(TaxSchema::TYPE);

        $this->getApiFacade()->update($taxCode, $attributes);

        return $this->getContentResponse($this->getApiFacade()->read($taxCode));
    }
}
