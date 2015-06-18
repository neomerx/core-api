<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\CoreApi\Api\Facades\ImageFormats;
use \Neomerx\CoreApi\Schemas\ImageFormatSchema;
use \Neomerx\CoreApi\Api\Images\ImageFormatsInterface;

/**
 * @package Neomerx\CoreApi
 */
final class ImageFormatsControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(ImageFormats::INTERFACE_BIND_NAME);
    }

    /**
     * Create a image format.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, $formatCode, $attributes) = $this->parseDocumentAsSingleData(ImageFormatSchema::TYPE);

        $attributes[ImageFormatsInterface::PARAM_CODE] = $formatCode;

        $resource = $this->getApiFacade()->create($attributes);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update image format.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function update($resourceCode)
    {
        $this->checkParametersEmpty();

        list(, , $attributes) = $this->parseDocumentAsSingleData(ImageFormatSchema::TYPE);

        $this->getApiFacade()->update($resourceCode, $attributes);

        return $this->getContentResponse($this->getApiFacade()->read($resourceCode));
    }
}
