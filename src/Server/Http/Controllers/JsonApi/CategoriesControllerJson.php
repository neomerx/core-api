<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Category;
use \Neomerx\CoreApi\Api\Facades\Categories;
use \Neomerx\CoreApi\Schemas\CategorySchema;
use \Neomerx\CoreApi\Api\Categories\CategoriesInterface;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * @package Neomerx\CoreApi
 */
final class CategoriesControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Categories::INTERFACE_BIND_NAME);
    }

    /**
     * Get all resources.
     *
     * @return Response
     */
    final public function index()
    {
        $this->checkParametersEmpty();

        /** @var Category $root */
        $root = $this->getApiFacade()->read(CategoriesInterface::ROOT_CODE);

        return $this->getContentResponse($root->getDescendants()->all());
    }

    /**
     * Create a category.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, $categoryCode, $attributes, $relationships) = $this->parseDocumentAsSingleData(CategorySchema::TYPE, [
            CategorySchema::REL_ANCESTOR => CategorySchema::TYPE,
        ]);

        $properties = S\arrayGetValueEx($attributes, CategorySchema::ATTR_PROPERTIES);

        $attributes[CategoriesInterface::PARAM_CODE]          = $categoryCode;
        $attributes[CategoriesInterface::PARAM_PROPERTIES]    = $properties;
        $attributes[CategoriesInterface::PARAM_ANCESTOR_CODE] =
            S\arrayGetValue2dEx($relationships, CategorySchema::REL_ANCESTOR, CategorySchema::TYPE);

        $resource = $this->getApiFacade()->create($attributes);

        return $this->getCreatedResponse($resource);
    }

    /**
     * Update category.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function update($resourceCode)
    {
        $this->checkParametersEmpty();

        list(, , $attributes, $relationships) = $this->parseDocumentAsSingleData(CategorySchema::TYPE, [
            CategorySchema::REL_ANCESTOR => CategorySchema::TYPE,
        ]);

        $ancestorCode = S\arrayGetValue2d($relationships, CategorySchema::REL_ANCESTOR, CategorySchema::TYPE);
        $properties   = S\arrayGetValue($attributes, CategorySchema::ATTR_PROPERTIES);

        $input = S\arrayFilterNulls(array_merge($attributes, [
            CategoriesInterface::PARAM_PROPERTIES    => $properties,
            CategoriesInterface::PARAM_ANCESTOR_CODE => $ancestorCode,
        ]));

        $this->getApiFacade()->update($resourceCode, $input);

        return $this->getContentResponse($this->getApiFacade()->read($resourceCode));
    }

    /**
     * Move category up.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function moveUp($resourceCode)
    {
        $this->checkParametersEmpty();

        $this->getApiFacade()->moveUp($resourceCode);

        return $this->getCodeResponse(SymfonyResponse::HTTP_NO_CONTENT);
    }

    /**
     * Move category down.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function moveDown($resourceCode)
    {
        $this->checkParametersEmpty();

        $this->getApiFacade()->moveDown($resourceCode);

        return $this->getCodeResponse(SymfonyResponse::HTTP_NO_CONTENT);
    }
}
