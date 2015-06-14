<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\CoreApi\Api\Facades\Languages;
use \Neomerx\CoreApi\Schemas\LanguageSchema;
use \Neomerx\CoreApi\Api\Languages\LanguagesInterface;

/**
 * @package Neomerx\CoreApi
 */
final class LanguagesControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Languages::INTERFACE_BIND_NAME);
    }

    /**
     * Create a language.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, $languageCode, $attributes) = $this->parseDocumentAsSingleData(LanguageSchema::TYPE);

        // language code is an attribute on database level so add it
        $attributes[LanguagesInterface::PARAM_ISO_CODE] = $languageCode;

        $resource = $this->getApiFacade()->create($attributes);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update language.
     *
     * @param string $languageCode
     *
     * @return Response
     */
    final public function update($languageCode)
    {
        $this->checkParametersEmpty();

        list(, , $attributes) = $this->parseDocumentAsSingleData(LanguageSchema::TYPE);

        $this->getApiFacade()->update($languageCode, $attributes);

        return $this->getContentResponse($this->getApiFacade()->read($languageCode));
    }
}
