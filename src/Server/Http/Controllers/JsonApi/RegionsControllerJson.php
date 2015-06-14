<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Api\Facades\Regions;
use \Neomerx\CoreApi\Schemas\RegionSchema;
use \Neomerx\CoreApi\Schemas\CountrySchema;
use \Neomerx\CoreApi\Api\Territories\RegionsInterface;

/**
 * @package Neomerx\CoreApi
 */
final class RegionsControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Regions::INTERFACE_BIND_NAME);
    }

    /**
     * Create a region.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, $regionCode, $attributes, $relationships) = $this->parseDocumentAsSingleData(RegionSchema::TYPE, [
                RegionSchema::REL_COUNTRY => CountrySchema::TYPE,
            ]);

        $attributes[RegionsInterface::PARAM_CODE]         = $regionCode;
        $attributes[RegionsInterface::PARAM_COUNTRY_CODE] = $relationships[RegionSchema::REL_COUNTRY];

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

        list(, , $attributes, $relationships) = $this->parseDocumentAsSingleData(RegionSchema::TYPE, [
            RegionSchema::REL_COUNTRY => CountrySchema::TYPE,
        ]);

        $attributes[RegionsInterface::PARAM_COUNTRY_CODE] = S\arrayGetValue($relationships, RegionSchema::REL_COUNTRY);

        $input = S\arrayFilterNulls($attributes);
        $this->getApiFacade()->update($resourceCode, $input);

        return $this->getContentResponse($this->getApiFacade()->read($resourceCode));
    }
}
