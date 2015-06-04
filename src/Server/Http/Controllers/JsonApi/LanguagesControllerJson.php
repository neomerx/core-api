<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Illuminate\Support\Facades\App;
use \Neomerx\CoreApi\Api\Facades\Languages;
use \Neomerx\CoreApi\Schemas\LanguageSchema;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Neomerx\CoreApi\Converters\LanguageConverterGeneric;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

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
        /** @noinspection PhpUndefinedMethodInspection */
        parent::__construct(Languages::INTERFACE_BIND_NAME, App::make(LanguageConverterGeneric::class));
    }

    /**
     * Get all languages.
     *
     * @return \Illuminate\Http\Response
     */
    final public function index()
    {
        $this->checkParametersEmpty();

        $resources = $this->getApiFacade()->search()->all();

        return $this->getContentResponse($resources);
    }

    /**
     * Create a language.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        $attributes = $this->getSingleDataAttributes(LanguageSchema::TYPE);

        $resource = $this->getApiFacade()->create($attributes);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Get language by code.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function show($resourceCode)
    {
        $this->checkParametersEmpty();

        $resource = $this->getApiFacade()->read($resourceCode);

        return $this->getContentResponse($resource);
    }

    /**
     * Update language.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function update($resourceCode)
    {
        $this->checkParametersEmpty();

        $attributes = $this->getSingleDataAttributes(LanguageSchema::TYPE);

        $this->getApiFacade()->update($resourceCode, $attributes);

        return $this->getCodeResponse(SymfonyResponse::HTTP_OK);
    }

    /**
     * Delete language.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function destroy($resourceCode)
    {
        $this->checkParametersEmpty();

        try {
            $this->getApiFacade()->delete($resourceCode);
        } catch (ModelNotFoundException $exception) {
            // ignore if resource not found
        }

        return $this->getCodeResponse(SymfonyResponse::HTTP_NO_CONTENT);
    }
}
