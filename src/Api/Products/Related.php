<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\ProductRelated;
use \Neomerx\CoreApi\Api\SingleResourceApi;
use \Neomerx\Core\Repositories\Products\ProductRepositoryInterface;
use \Neomerx\Core\Repositories\Products\ProductRelatedRepositoryInterface;

class Related extends SingleResourceApi implements RelatedInterface
{
    const EVENT_PREFIX = 'Api.ProductRelated.';

    /**
     * @var ProductRelatedRepositoryInterface
     */
    private $relatedRepo;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @param ProductRelatedRepositoryInterface $relatedRepo
     * @param ProductRepositoryInterface        $productRepo
     */
    public function __construct(ProductRelatedRepositoryInterface $relatedRepo, ProductRepositoryInterface $productRepo)
    {
        $this->relatedRepo = $relatedRepo;
        $this->productRepo = $productRepo;
    }
//
//    /**
//     * @param Product $product
//     * @param array   $relatedProducts
//     *
//     * @return void
//     */
//    public function store(Product $product, array $relatedProducts)
//    {
//        Permissions::check($product, Permission::edit());
//
//        /** @var Product $relatedProduct */
//        foreach ($relatedProducts as $relatedProduct) {
//            $this->relatedRepo->instance($product, $relatedProduct)->saveOrFail();
//        }
//    }
//
//    /**
//     * Read related products.
//     *
//     * @param Product $product
//     *
//     * @return Collection
//     */
//    public function show(Product $product)
//    {
//        /** @noinspection PhpUndefinedMethodInspection */
//        $related = $product->relatedProducts()->with([
//            Product::withDefaultCategory(),
//            Product::withManufacturer(),
//            Product::withProperties(),
//            Product::withTaxType(),
//        ])->get();
//        return $related;
//    }
//
//    /**
//     * Set related products to product.
//     *
//     * @param Product $product
//     * @param array $productSKUs
//     *
//     * @return void
//     */
//    public function updateRelated(Product $product, array $productSKUs)
//    {
//        Permissions::check($product, Permission::edit());
//
//        $productIds  = $this->productModel->selectByCodes($productSKUs)->lists(Product::FIELD_ID);
//        /** @noinspection PhpUndefinedMethodInspection */
//        $relatedIds  = $product->related()->lists(ProductRelated::FIELD_ID_RELATED_PRODUCT);
//        $toAdd       = array_diff($productIds, $relatedIds);
//        $toRemove    = array_diff($relatedIds, $productIds);
//
//        /** @noinspection PhpUndefinedMethodInspection */
//        DB::beginTransaction();
//        try {
//
//            // Add
//            foreach ($toAdd as $relatedProductId) {
//                /** @var \Neomerx\Core\Models\ProductRelated $relatedProduct */
//                /** @noinspection PhpUndefinedMethodInspection */
//                $relatedProduct = App::make(ProductRelated::class);
//                $relatedProduct->fill([
//                    ProductRelated::FIELD_ID_RELATED_PRODUCT => $relatedProductId,
//                ]);
//
//                $isRelatedAdded =  $product->related()->save($relatedProduct);
//                $isRelatedAdded =  ($isRelatedAdded and $isRelatedAdded->exists);
//                $isRelatedAdded ?: S\throwEx(new ValidationException($relatedProduct->getValidator()));
//            }
//
//            // Remove
//            /** @noinspection PhpUndefinedMethodInspection */
//            $toRemove = $product->related()
//                ->whereIn(ProductRelated::FIELD_ID_RELATED_PRODUCT, $toRemove)
//                ->lists(ProductRelated::FIELD_ID);
//            $this->relatedRepo->destroy($toRemove);
//
//            $allExecutedOk = true;
//
//        } finally {
//            /** @noinspection PhpUndefinedMethodInspection */
//            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
//        }
//
//        Event::fire(new ProductArgs(Products::EVENT_PREFIX . 'updatedRelated', $product));
//    }
//
//    /**
//     * @param Product $product
//     * @param array   $excludeProducts
//     *
//     * @return void
//     */
//    public function destroy(Product $product, array $excludeProducts)
//    {
//        Permissions::check($product, Permission::edit());
//
//        $productIdsToExclude = [];
//        foreach ($excludeProducts as $toExclude) {
//            $productIdsToExclude[] = $toExclude->{Product::FIELD_ID};
//        }
//
//        /** @var ProductRelated $relatedProduct */
//        foreach ($product->related as $relatedProduct) {
//            $relatedId = $relatedProduct->{ProductRelated::FIELD_ID_RELATED_PRODUCT};
//            if (isset($productIdsToExclude[$relatedId]) === true) {
//                $relatedProduct->deleteOrFail();
//            }
//        }
//    }
    /**
     * @inheritdoc
     */
    protected function getResourceRepository()
    {
        return $this->relatedRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            ProductRelated::withProduct(),
            ProductRelated::withRelated(),
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
        /** @var ProductRelated $resource */
        return new RelatedArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Product $product */
        $product = $this->keyToModelEx($input, self::PARAM_PRODUCT_SKU, $this->productRepo);

        /** @var Product $related */
        $related = $this->keyToModelEx($input, self::PARAM_RELATED_SKU, $this->productRepo);

        return $this->relatedRepo->instance($product, $related);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var ProductRelated $resource */

        /** @var Product $product */
        $product = $this->keyToModel($input, self::PARAM_PRODUCT_SKU, $this->productRepo);

        /** @var Product $related */
        $related = $this->keyToModel($input, self::PARAM_RELATED_SKU, $this->productRepo);

        $this->relatedRepo->fill($resource, $product, $related);

        return $resource;
    }
}
