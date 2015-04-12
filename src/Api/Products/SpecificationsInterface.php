<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\Specification;
use \Illuminate\Database\Eloquent\Collection;

interface SpecificationsInterface extends CrudInterface
{
    const PARAM_ID          = Specification::FIELD_ID;
    const PARAM_PRODUCT_SKU = 'product_sku';
    const PARAM_VARIANT_SKU = 'variant_sku';
    const PARAM_VALUE_CODE  = 'value_code';
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
