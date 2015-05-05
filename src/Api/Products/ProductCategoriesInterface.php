<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ProductCategory;

/**
 * @package Neomerx\CoreApi
 */
interface ProductCategoriesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_ID            = ProductCategory::FIELD_ID;
    /** Parameter key */
    const PARAM_PRODUCT_SKU   = 'product_sku';
    /** Parameter key */
    const PARAM_CATEGORY_CODE = 'category_code';
    /** Parameter key */
    const PARAM_POSITION      = ProductCategory::FIELD_POSITION;

    /**
     * Create product category.
     *
     * @param array $input
     *
     * @return ProductCategory
     */
    public function create(array $input);

    /**
     * Read resource by identifier.
     *
     * @param int $idx
     *
     * @return ProductCategory
     */
    public function read($idx);
}
