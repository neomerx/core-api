<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\Category;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\ProductCategory;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Products\ProductRepositoryInterface;
use \Neomerx\Core\Repositories\Categories\CategoryRepositoryInterface;
use \Neomerx\Core\Repositories\Products\ProductCategoryRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductCategories extends SingleResourceApi implements ProductCategoriesInterface
{
    const EVENT_PREFIX = 'Api.ProductRelated.';

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var ProductCategoryRepositoryInterface
     */
    private $productCategoryRepo;

    /**
     * @param ProductCategoryRepositoryInterface $productCategoryRepo
     * @param CategoryRepositoryInterface        $categoryRepo
     * @param ProductRepositoryInterface         $productRepo
     */
    public function __construct(
        ProductCategoryRepositoryInterface $productCategoryRepo,
        CategoryRepositoryInterface $categoryRepo,
        ProductRepositoryInterface $productRepo
    ) {
        $this->productRepo         = $productRepo;
        $this->categoryRepo        = $categoryRepo;
        $this->productCategoryRepo = $productCategoryRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->productCategoryRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            ProductCategory::withProduct(),
            ProductCategory::withCategory(),
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
        /** @var ProductCategory $resource */
        return new ProductCategoryArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Product $product */
        $product = $this->keyToModelEx($input, self::PARAM_PRODUCT_SKU, $this->productRepo);

        /** @var Category $category */
        $category = $this->keyToModelEx($input, self::PARAM_CATEGORY_CODE, $this->categoryRepo);

        return $this->productCategoryRepo->instance($product, $category, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var ProductCategory $resource */

        /** @var Product $product */
        $product = $this->keyToModel($input, self::PARAM_PRODUCT_SKU, $this->productRepo);

        /** @var Category $category */
        $category = $this->keyToModel($input, self::PARAM_CATEGORY_CODE, $this->categoryRepo);

        $this->productCategoryRepo->fill($resource, $product, $category);

        return $resource;
    }
}
