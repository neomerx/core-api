<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\Specification;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Models\CharacteristicValue;
use \Neomerx\Core\Repositories\Features\ValueRepositoryInterface;
use \Neomerx\Core\Repositories\Products\ProductRepositoryInterface;
use \Neomerx\Core\Repositories\Products\VariantRepositoryInterface;
use \Neomerx\Core\Repositories\Products\SpecificationRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @package Neomerx\CoreApi
 */
class Specifications extends SingleResourceApi implements SpecificationsInterface
{
    /** Event prefix */
    const EVENT_PREFIX = 'Api.ProductRelated.';

    /**
     * @var VariantRepositoryInterface
     */
    private $variantRepo;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var SpecificationRepositoryInterface
     */
    private $specificationRepo;

    /**
     * @var ValueRepositoryInterface
     */
    private $valueRepo;

    /**
     * @param SpecificationRepositoryInterface $specificationRepo
     * @param VariantRepositoryInterface       $variantRepo
     * @param ProductRepositoryInterface       $productRepo
     * @param ValueRepositoryInterface         $valueRepo
     */
    public function __construct(
        SpecificationRepositoryInterface $specificationRepo,
        VariantRepositoryInterface $variantRepo,
        ProductRepositoryInterface $productRepo,
        ValueRepositoryInterface $valueRepo
    ) {
        $this->specificationRepo = $specificationRepo;
        $this->productRepo       = $productRepo;
        $this->variantRepo       = $variantRepo;
        $this->valueRepo         = $valueRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->specificationRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            Specification::withProduct(),
            Specification::withVariant(),
            Specification::withValue(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Specification $resource */
        return new SpecificationArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Product $product */
        $product = $this->keyToModelEx($input, self::PARAM_PRODUCT_SKU, $this->productRepo);

        /** @var Variant $variant */
        $variant = $this->keyToModel($input, self::PARAM_VARIANT_SKU, $this->variantRepo);

        /** @var CharacteristicValue $value */
        $value = $this->keyToModelEx($input, self::PARAM_VALUE_CODE, $this->valueRepo);

        return $this->specificationRepo->instance($product, $value, $input, $variant);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Specification $resource */

        /** @var Product $product */
        $product = $this->keyToModel($input, self::PARAM_PRODUCT_SKU, $this->productRepo);

        /** @var Variant $variant */
        $variant = $this->keyToModel($input, self::PARAM_VARIANT_SKU, $this->variantRepo);

        /** @var CharacteristicValue $value */
        $value = $this->keyToModel($input, self::PARAM_VALUE_CODE, $this->valueRepo);

        $this->specificationRepo->fill($resource, $product, $value, $input, $variant);

        return $resource;
    }
}
