<?php namespace Neomerx\CoreApi\Api\Products;

use \Neomerx\Core\Models\Variant;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\VariantProperties;
use \Illuminate\Database\Eloquent\Collection;

interface VariantsInterface extends CrudInterface
{
    const PARAM_PRODUCT_SKU            = 'product_sku';
    const PARAM_SKU            = Variant::FIELD_SKU;
    const PARAM_PRICE_WO_TAX           = Variant::FIELD_PRICE_WO_TAX;
    const PARAM_PROPERTIES             = Variant::FIELD_PROPERTIES;
    const PARAM_PROPERTIES_NAME        = VariantProperties::FIELD_NAME;
    const PARAM_PROPERTIES_DESCRIPTION = VariantProperties::FIELD_DESCRIPTION;
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
