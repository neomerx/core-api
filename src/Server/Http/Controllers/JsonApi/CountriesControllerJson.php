<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Api\Facades\Countries;
use \Neomerx\CoreApi\Schemas\CountrySchema;
use \Neomerx\CoreApi\Api\Territories\CountriesInterface;

/**
 * @package Neomerx\CoreApi
 */
final class CountriesControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Countries::INTERFACE_BIND_NAME);
    }

    /**
     * Create a resource.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, $resourceCode, $attributes) = $this->parseDocumentAsSingleData(CountrySchema::TYPE);

        $properties = S\arrayGetValueEx($attributes, CountrySchema::ATTR_PROPERTIES);
        $resource = $this->getApiFacade()->create([
            CountriesInterface::PARAM_CODE       => $resourceCode,
            CountriesInterface::PARAM_PROPERTIES => $properties,
        ]);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update resource.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function update($resourceCode)
    {
        $this->checkParametersEmpty();

        list(, , $attributes) = $this->parseDocumentAsSingleData(CountrySchema::TYPE);

        $properties = S\arrayGetValueEx($attributes, CountrySchema::ATTR_PROPERTIES);

        $input = S\arrayFilterNulls([
            CountriesInterface::PARAM_PROPERTIES => $properties,
        ]);
        $this->getApiFacade()->update($resourceCode, $input);

        return $this->getContentResponse($this->getApiFacade()->read($resourceCode));
    }
}
