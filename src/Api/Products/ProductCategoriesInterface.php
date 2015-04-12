<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ProductCategory;

interface ProductCategoriesInterface extends CrudInterface
{
    const PARAM_ID            = ProductCategory::FIELD_ID;
    const PARAM_PRODUCT_SKU   = 'product_sku';
    const PARAM_CATEGORY_CODE = 'category_code';
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
