<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Input;
use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Api\Facades\Images;
use \Neomerx\CoreApi\Schemas\ImageSchema;
use \Neomerx\CoreApi\Api\Images\ImagesInterface;

/**
 * @package Neomerx\CoreApi
 */
final class ImagesControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Images::INTERFACE_BIND_NAME);
    }

    /**
     * Create a resource.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, , $attributes) = $this->parseDocumentAsSingleData(ImageSchema::TYPE);

        $fileData   = Input::file(ImagesInterface::PARAM_ORIGINAL_FILE_DATA);
        $properties = S\arrayGetValueEx($attributes, ImageSchema::ATTR_PROPERTIES);
        $resource = $this->getApiFacade()->create([
            ImagesInterface::PARAM_PROPERTIES         => $properties,
            ImagesInterface::PARAM_ORIGINAL_FILE_DATA => $fileData,
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

        list(, , $attributes) = $this->parseDocumentAsSingleData(ImageSchema::TYPE);

        $properties = S\arrayGetValueEx($attributes, ImageSchema::ATTR_PROPERTIES);

        $input = S\arrayFilterNulls([
            ImagesInterface::PARAM_PROPERTIES => $properties,
        ]);
        $this->getApiFacade()->update($resourceCode, $input);

        return $this->getContentResponse($this->getApiFacade()->read($resourceCode));
    }
}
