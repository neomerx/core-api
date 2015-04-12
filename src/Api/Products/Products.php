<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\Category;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\Core\Models\Manufacturer;
use \Neomerx\Core\Models\ProductTaxType;
use \Neomerx\Core\Support\SearchGrammar;
use \Neomerx\Core\Models\ProductProperties;
use \Neomerx\CoreApi\Api\ResourceWithPropertiesApi;
use \Neomerx\Core\Repositories\RepositoryInterface;
use \Neomerx\Core\Repositories\Products\ProductRepositoryInterface;
use \Neomerx\Core\Repositories\Languages\LanguageRepositoryInterface;
use \Neomerx\Core\Repositories\Categories\CategoryRepositoryInterface;
use \Neomerx\Core\Repositories\Products\ProductTaxTypeRepositoryInterface;
use \Neomerx\Core\Repositories\Manufacturers\ManufacturerRepositoryInterface;
use \Neomerx\Core\Repositories\Products\ProductPropertiesRepositoryInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Products extends ResourceWithPropertiesApi implements ProductsInterface
{
    const EVENT_PREFIX = 'Api.Product.';
    const BIND_NAME    = __CLASS__;
//
//    /**
//     * @var Categories
//     */
//    private $categories;
//
//    /**
//     * @var Related
//     */
//    private $related;
//
//    /**
//     * @var ProductSpecification
//     */
//    private $productSpecification;
//
//    /**
//     * @var VariantSpecification
//     */
//    private $variantSpecification;
//
//    /**
//     * @var ProductImages
//     */
//    private $productImage;
//
//    /**
//     * @var VariantImage
//     */
//    private $variantImage;
//
//    /**
//     * @var ProductCrud
//     */
//    private $productCrud;
//
//    /**
//     * @var VariantCrud
//     */
//    private $variantCrud;
//
//    /**
//     * @param Product             $product
//     * @param ProductProperties   $properties
//     * @param Language            $language
//     * @param Manufacturer        $manufacturer
//     * @param Category            $category
//     * @param ProductCategory     $productCategory
//     * @param ProductRelated      $productRelated
//     * @param CharacteristicValue $characteristicValue
//     * @param Variant             $variant
//     * @param Specification       $specification
//     * @param ProductImage        $productImage
//     * @param VariantProperties   $variantProperties
//     * @param ProductTaxType      $taxType
//     *
//     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
//     */
//    public function __construct(
//        Product $product,
//        ProductProperties $properties,
//        Language $language,
//        Manufacturer $manufacturer,
//        Category $category,
//        ProductCategory $productCategory,
//        ProductRelated $productRelated,
//        CharacteristicValue $characteristicValue,
//        Variant $variant,
//        Specification $specification,
//        ProductImage $productImage,
//        VariantProperties $variantProperties,
//        ProductTaxType $taxType
//    ) {
//        $this->productCrud = new ProductCrud($product, $properties, $category, $manufacturer, $taxType, $language);
//        $this->variantCrud = new VariantCrud($product, $variant, $variantProperties, $language);
//
//        $this->categories = new Categories($product, $category, $productCategory);
//        $this->related    = new Related($product, $productRelated, ProductCrud::$relations);
//
//        $this->productImage = new ProductImages($product, $productImage, $language);
//        $this->variantImage = new VariantImage($variant, $productImage, $language);
//
//        $this->productSpecification = new ProductSpecification($product, $specification, $characteristicValue);
//        $this->variantSpecification = new VariantSpecification($variant, $characteristicValue);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function showCategories(Product $product)
//    {
//        return $this->categories->showCategories($product);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function updateCategories(Product $product, array $categoryCodes)
//    {
//        $this->categories->updateCategories($product, $categoryCodes);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function showRelated(Product $product)
//    {
//        return $this->related->showRelated($product);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function updateRelated(Product $product, array $productSKUs)
//    {
//        $this->related->updateRelated($product, $productSKUs);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function showProductSpecification(Product $product)
//    {
//        return $this->productSpecification->showProductSpecification($product);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function storeProductSpecification(Product $product, array $valueCodes)
//    {
//        $this->productSpecification->storeProductSpecification($product, $valueCodes);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function destroyProductSpecification(Product $product, array $valueCodes)
//    {
//        $this->productSpecification->destroyProductSpecification($product, $valueCodes);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function updateProductSpecification(Product $product, array $parameters = [])
//    {
//        $this->productSpecification->updateProductSpecification($product, $parameters);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function makeSpecificationVariable(Product $product, $valueCode)
//    {
//        $this->productSpecification->makeSpecificationVariable($product, $valueCode);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function updateVariantSpecification(Variant $variant, array $parameters = [])
//    {
//        $this->variantSpecification->updateVariantSpecification($variant, $parameters);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function makeSpecificationNonVariable(Variant $variant, $valueCode)
//    {
//        $this->variantSpecification->makeSpecificationNonVariable($variant, $valueCode);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function showProductImages(Product $product)
//    {
//        return $this->productImage->showProductImages($product);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function storeProductImages(Product $product, array $descriptions, array $files)
//    {
//        $this->productImage->storeProductImages($product, $descriptions, $files);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function setDefaultProductImage(Product $product, $imageId)
//    {
//        $this->productImage->setDefaultProductImage($product, $imageId);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function destroyProductImage(Product $product, $imageId)
//    {
//        $this->productImage->destroyProductImage($product, $imageId);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function showVariantImages(Variant $variant)
//    {
//        return $this->variantImage->showVariantImages($variant);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function storeVariantImages(Variant $variant, array $descriptions, array $files)
//    {
//        $this->variantImage->storeVariantImages($variant, $descriptions, $files);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function destroyVariantImage(Variant $variant, $imageId)
//    {
//        $this->variantImage->destroyVariantImage($variant, $imageId);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function storeVariant(Product $product, array $input)
//    {
//        $this->variantCrud->create($product, $input);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function updateVariant(Variant $variant, array $input)
//    {
//        $this->variantCrud->update($variant, $input);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function destroyVariant($variantSKU)
//    {
//        $this->variantCrud->delete($variantSKU);
//    }

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var ProductPropertiesRepositoryInterface
     */
    private $propertiesRepo;

    /**
     * @param ProductRepositoryInterface           $productRepo
     * @param ProductPropertiesRepositoryInterface $propertiesRepo
     * @param LanguageRepositoryInterface          $languageRepo
     * @param CategoryRepositoryInterface          $categoryRepo
     * @param ManufacturerRepositoryInterface      $manufacturerRepo
     * @param ProductTaxTypeRepositoryInterface    $taxTypeRepo
     */
    public function __construct(
        ProductRepositoryInterface $productRepo,
        ProductPropertiesRepositoryInterface $propertiesRepo,
        LanguageRepositoryInterface $languageRepo,
        CategoryRepositoryInterface $categoryRepo,
        ManufacturerRepositoryInterface $manufacturerRepo,
        ProductTaxTypeRepositoryInterface $taxTypeRepo
    ) {
        parent::__construct($languageRepo);
        $this->productRepo      = $productRepo;
        $this->propertiesRepo   = $propertiesRepo;
        $this->categoryRepo     = $categoryRepo;
        $this->manufacturerRepo = $manufacturerRepo;
        $this->taxTypeRepo      = $taxTypeRepo;
    }

    /**
     * @return RepositoryInterface
     */
    protected function getResourceRepository()
    {
        return $this->productRepo;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceRelations()
    {
        return [
            Product::withProperties(),
            Product::withManufacturer(),
            Product::withDefaultCategory(),
            Product::withTaxType(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getSearchRules()
    {
        return [
            Product::FIELD_SKU          => SearchGrammar::TYPE_STRING,
            Product::FIELD_LINK         => SearchGrammar::TYPE_STRING,
            Product::FIELD_ENABLED      => SearchGrammar::TYPE_BOOL,
            Product::FIELD_PRICE_WO_TAX => SearchGrammar::TYPE_FLOAT,
            'created'                   => [SearchGrammar::TYPE_DATE, Product::FIELD_CREATED_AT],
            'updated'                   => [SearchGrammar::TYPE_DATE, Product::FIELD_UPDATED_AT],
            SearchGrammar::LIMIT_SKIP   => SearchGrammar::TYPE_LIMIT,
            SearchGrammar::LIMIT_TAKE   => SearchGrammar::TYPE_LIMIT,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getEvent(BaseModel $resource, $eventNamePostfix)
    {
        /** @var Product $resource */
        return new ProductArgs(self::EVENT_PREFIX.$eventNamePostfix, $resource);
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyInstance(BaseModel $resource, Language $language, array $attributes)
    {
        /** @var Product $resource */
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
        /** @var Product $resource */
        /** @var ProductProperties $property */
        $this->propertiesRepo->fill($property, $resource, $language, $attributes);
        return $property;
    }

    /**
     * @inheritdoc
     */
    protected function getResourceProperties(BaseModel $resource)
    {
        /** @var Product $resource */
        return $resource->{Product::FIELD_PROPERTIES};
    }

    /**
     * @inheritdoc
     */
    protected function getPropertyLanguageIdKey()
    {
        return ProductProperties::FIELD_ID_LANGUAGE;
    }

    /**
     * @inheritdoc
     */
    protected function getInstance(array $input)
    {
        /** @var Category $category */
        $category = $this->keyToModelEx($input, self::PARAM_DEFAULT_CATEGORY_CODE, $this->categoryRepo);

        /** @var Manufacturer $manufacturer */
        $manufacturer = $this->keyToModelEx($input, self::PARAM_MANUFACTURER_CODE, $this->manufacturerRepo);

        /** @var ProductTaxType $taxType */
        $taxType = $this->keyToModelEx($input, self::PARAM_TAX_TYPE_CODE, $this->taxTypeRepo);

        return $this->productRepo->instance($category, $manufacturer, $taxType, $input);
    }

    /**
     * @inheritdoc
     */
    protected function fillResource(BaseModel $resource, array $input)
    {
        /** @var Product $resource */

        /** @var Category $category */
        $category = $this->keyToModel($input, self::PARAM_DEFAULT_CATEGORY_CODE, $this->categoryRepo);

        /** @var Manufacturer $manufacturer */
        $manufacturer = $this->keyToModel($input, self::PARAM_MANUFACTURER_CODE, $this->manufacturerRepo);

        /** @var ProductTaxType $taxType */
        $taxType = $this->keyToModel($input, self::PARAM_TAX_TYPE_CODE, $this->taxTypeRepo);

        $this->productRepo->fill($resource, $category, $manufacturer, $taxType, $input);
        return $resource;
    }
}
