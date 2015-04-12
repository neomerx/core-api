<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\ProductRelated;
use \Illuminate\Database\Eloquent\Collection;

interface RelatedInterface extends CrudInterface
{
    const PARAM_ID          = ProductRelated::FIELD_ID;
    const PARAM_PRODUCT_SKU = 'product_sku';
    const PARAM_RELATED_SKU = 'related_sku';

    /**
     * Create product.
     *
     * @param array $input
     *
     * @return ProductRelated
     */
    public function create(array $input);

    /**
     * Read resource by identifier.
     *
     * @param int $idx
     *
     * @return ProductRelated
     */
    public function read($idx);

    /**
     * Search products.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
