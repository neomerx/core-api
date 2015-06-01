<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\VariantProperties;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\Core\Repositories\Products\ProductRepositoryInterface;
use \Neomerx\Core\Repositories\Products\VariantRepositoryInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Products\VariantPropertiesRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @package Neomerx\CoreApi
 */
class Variants extends ResourceWithPropertiesApi implements VariantsInterface
{
    /** Event prefix */
    const EVENT_PREFIX = 'Api.Variant.';

    /**
     * @var VariantRepositoryInterface
     */
    private $variantRepo;

    /**
     * @var VariantPropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @var VariantRepositoryInterface
     */
    private $productRepo;

    /**
     * @param VariantRepositoryInterface           $variantRepo
     * @param VariantPropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface          $languageRepo
     * @param ProductRepositoryInterface           $productRepo
     */
    public function __construct(
        VariantRepositoryInterface $variantRepo,
        VariantPropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo,
        ProductRepositoryInterface $productRepo
    ) {
        parent::__construct($languageRepo);
        $this->variantRepo    = $variantRepo;
        $this->propertiesRepo = $propertiesRepo;
        $this->productRepo    = $productRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->variantRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            Variant::withProperties(),
            Variant::withManufacturer(),
            Variant::withDefaultCategory(),
            Variant::withTaxType(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Variant::FIELD_SKU          => SearchGrammar::TYPE_STRING,
            Variant::FIELD_PRICE_WO_TAX => SearchGrammar::TYPE_FLOAT,
            'created'                   => [SearchGrammar::TYPE_DATE, Variant::FIELD_CREATED_AT],
            'updated'                   => [SearchGrammar::TYPE_DATE, Variant::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP   => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE   => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Variant $resource */
        return new VariantArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var Variant $resource */
        return $this->propertiesRepo->instance($resource, $language, $attributes);
    }

    /**
     * @inheritdoc
     */
    protected function fillProperty(
        BaseModel $property,
        BaseModel $resource,
        Language $language,
        array $attributes
    ) {
        /** @var Variant $resource */
        /** @var VariantProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var Variant $resource */
        return $resource->{Variant::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return VariantProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Product $product */
        $product = $this->keyToModelEx($input, self::PARAM_PRODUCT_SKU, $this->productRepo);

        return $this->variantRepo->instance($product, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Variant $resource */

        /** @var Product $product */
        $product = $this->keyToModel($input, self::PARAM_PRODUCT_SKU, $this->productRepo);

        $this->variantRepo->fill($resource, $product, $input);
        return $resource;
    }
}
