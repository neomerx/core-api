<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Models\Variant;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\VariantProperties;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface VariantsInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_PRODUCT_SKU            = 'product_sku';
    /** Parameter key */
    const PARAM_SKU                    = Variant::FIELD_SKU;
    /** Parameter key */
    const PARAM_PRICE_WO_TAX           = Variant::FIELD_PRICE_WO_TAX;
    /** Parameter key */
    const PARAM_PROPERTIES             = Variant::FIELD_PROPERTIES;
    /** Parameter key */
    const PARAM_PROPERTIES_NAME        = VariantProperties::FIELD_NAME;
    /** Parameter key */
    const PARAM_PROPERTIES_DESCRIPTION = VariantProperties::FIELD_DESCRIPTION;
    /** Parameter key */
    const PARAM_IMAGES                 = Variant::FIELD_IMAGES;

    /**
     * Create product.
     *
     * @param array $input
     *
     * @return Variant
     */
    public function create(array $input);

    /**
     * Read resource by identifier.
     *
     * @param string $code
     *
     * @return Variant
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
}
