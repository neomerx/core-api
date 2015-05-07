<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Models\Product;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ProductProperties;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface ProductsInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_SKU                          = Product::FIELD_SKU;
    /** Parameter key */
    const PARAM_LINK                         = Product::FIELD_LINK;
    /** Parameter key */
    const PARAM_ENABLED                      = Product::FIELD_ENABLED;
    /** Parameter key */
    const PARAM_PRICE_WO_TAX                 = Product::FIELD_PRICE_WO_TAX;
    /** Parameter key */
    const PARAM_PKG_HEIGHT                   = Product::FIELD_PKG_HEIGHT;
    /** Parameter key */
    const PARAM_PKG_WIDTH                    = Product::FIELD_PKG_WIDTH;
    /** Parameter key */
    const PARAM_PKG_LENGTH                   = Product::FIELD_PKG_LENGTH;
    /** Parameter key */
    const PARAM_PKG_WEIGHT                   = Product::FIELD_PKG_WEIGHT;
    /** Parameter key */
    const PARAM_TAX_TYPE_CODE                = 'tax_type_code';
    /** Parameter key */
    const PARAM_MANUFACTURER_CODE            = 'manufacturer_code';
    /** Parameter key */
    const PARAM_DEFAULT_CATEGORY_CODE        = 'default_category_code';
    /** Parameter key */
    const PARAM_PROPERTIES                   = Product::FIELD_PROPERTIES;
    /** Parameter key */
    const PARAM_PROPERTIES_NAME              = ProductProperties::FIELD_NAME;
    /** Parameter key */
    const PARAM_PROPERTIES_DESCRIPTION       = ProductProperties::FIELD_DESCRIPTION;
    /** Parameter key */
    const PARAM_PROPERTIES_DESCRIPTION_SHORT = ProductProperties::FIELD_DESCRIPTION_SHORT;
    /** Parameter key */
    const PARAM_PROPERTIES_META_TITLE        = ProductProperties::FIELD_META_TITLE;
    /** Parameter key */
    const PARAM_PROPERTIES_META_KEYWORDS     = ProductProperties::FIELD_META_KEYWORDS;
    /** Parameter key */
    const PARAM_PROPERTIES_META_DESCRIPTION  = ProductProperties::FIELD_META_DESCRIPTION;
    /** Parameter key */
    const PARAM_IMAGES                       = Product::FIELD_IMAGES;

    /**
     * Create product.
     *
     * @param array $input
     *
     * @return Product
     */
    public function create(array $input);

    /**
     * Read resource by identifier.
     *
     * @param string $code
     *
     * @return Product
     */
    public function read($code);

    /**
     * Search products.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
//
//    /**
//     * Set related products to product.
//     *
//     * @param Product $product
//     * @param array   $productSKUs
//     *
//     * @return void
//     */
//    public function updateRelated(Product $product, array $productSKUs);
//
//    /**
//     * Update characteristic values in product specification.
//     *
//     * @param Product $product
//     * @param array   $parameters
//     *
//     * @return void
//     */
//    public function updateProductSpecification(Product $product, array $parameters = []);
//
//    /**
//     * Add product variants.
//     *
//     * @param Product $product
//     * @param array   $input
//     *
//     * @return void
//     */
//    public function storeVariant(Product $product, array $input);
//
//    /**
//     * Read variant images.
//     *
//     * @param Variant $variant
//     *
//     * @return Collection
//     */
//    public function showVariantImages(Variant $variant);
//
//    /**
//     * Update variant specification.
//     *
//     * @param Variant $variant
//     * @param array   $parameters
//     *
//     * @return void
//     */
//    public function updateVariantSpecification(Variant $variant, array $parameters = []);
//
//    /**
//     * Read related products.
//     *
//     * @param Product $product
//     *
//     * @return Collection
//     */
//    public function showRelated(Product $product);
//
//    /**
//     * Update product variant.
//     *
//     * @param Variant $variant
//     * @param array   $input
//     *
//     * @return void
//     */
//    public function updateVariant(Variant $variant, array $input);
//
//    /**
//     * Remove variant images.
//     *
//     * @param Variant $variant
//     * @param int     $imageId
//     *
//     * @return void
//     */
//    public function destroyVariantImage(Variant $variant, $imageId);
//
//    /**
//     * Remove product variants.
//     *
//     * @param string $variantSKU
//     *
//     * @return void
//     */
//    public function destroyVariant($variantSKU);
//
//    /**
//     * Add product images.
//     *
//     * @param Product $product
//     * @param array   $descriptions
//     * @param array   $files
//     *
//     * @return void
//     */
//    public function storeProductImages(Product $product, array $descriptions, array $files);
//
//    /**
//     * Set product image as cover.
//     *
//     * @param Product $product
//     * @param int     $imageId
//     *
//     * @return void
//     */
//    public function setDefaultProductImage(Product $product, $imageId);
//
//    /**
//     * Read product additional categories.
//     *
//     * @param Product $product
//     *
//     * @return Collection
//     */
//    public function showCategories(Product $product);
//
//    /**
//     * Make specification non variable.
//     *
//     * @param Variant $variant
//     * @param string  $valueCode
//     *
//     * @return void
//     */
//    public function makeSpecificationNonVariable(Variant $variant, $valueCode);
//
//    /**
//     * Read product images.
//     *
//     * @param Product $product
//     *
//     * @return Collection
//     */
//    public function showProductImages(Product $product);
//
//    /**
//     * Read product specification.
//     *
//     * @param Product $product
//     *
//     * @return Collection
//     */
//    public function showProductSpecification(Product $product);
//
//    /**
//     * Add characteristic values to product specification.
//     *
//     * @param Product $product
//     * @param array   $valueCodes
//     *
//     * @return void
//     */
//    public function storeProductSpecification(Product $product, array $valueCodes);
//
//    /**
//     * Remove characteristic values from product specification.
//     *
//     * @param Product $product
//     * @param array   $valueCodes
//     *
//     * @return void
//     */
//    public function destroyProductSpecification(Product $product, array $valueCodes);
//
//    /**
//     * Remove product images.
//     *
//     * @param Product $product
//     * @param int     $imageId
//     *
//     * @return void
//     */
//    public function destroyProductImage(Product $product, $imageId);
//
//    /**
//     * Add variant images.
//     *
//     * @param Variant $variant
//     * @param array   $descriptions
//     * @param array   $files
//     *
//     * @return void
//     */
//    public function storeVariantImages(Variant $variant, array $descriptions, array $files);
//
//    /**
//     * Make specification variable.
//     *
//     * @param Product $product
//     * @param string  $valueCode
//     *
//     * @return void
//     */
//    public function makeSpecificationVariable(Product $product, $valueCode);
//
//    /**
//     * Set categories to product.
//     *
//     * @param Product $product
//     * @param array   $categoryCodes
//     *
//     * @return void
//     */
//    public function updateCategories(Product $product, array $categoryCodes);
}
