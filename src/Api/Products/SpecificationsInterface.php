<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\Specification;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface SpecificationsInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_ID          = Specification::FIELD_ID;
    /** Parameter key */
    const PARAM_PRODUCT_SKU = 'product_sku';
    /** Parameter key */
    const PARAM_VARIANT_SKU = 'variant_sku';
    /** Parameter key */
    const PARAM_VALUE_CODE  = 'value_code';
    /** Parameter key */
    const PARAM_POSITION    = Specification::FIELD_POSITION;

    /**
     * Create product specification.
     *
     * @param array $input
     *
     * @return Specification
     */
    public function create(array $input);

    /**
     * Read resource by identifier.
     *
     * @param int $idx
     *
     * @return Specification
     */
    public function read($idx);

    /**
     * Search product specifications.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
