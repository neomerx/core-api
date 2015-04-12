<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\ProductProperties;
use \Neomerx\CoreApi\Api\Products\ProductsInterface as Api;

class ProductConverterGeneric extends BasicConverterWithLanguageFilter
{
    use LanguagePropertiesTrait;

    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param Product $product
     *
     * @return array
     */
    public function convert($product = null)
    {
        if ($product === null) {
            return null;
        }

        assert('$product instanceof '.Product::class);

        $result = $product->attributesToArray();

        $result[Api::PARAM_MANUFACTURER_CODE]     = $product->{Product::FIELD_MANUFACTURER}->code;
        $result[Api::PARAM_DEFAULT_CATEGORY_CODE] = $product->{Product::FIELD_DEFAULT_CATEGORY}->code;
        $result[Api::PARAM_TAX_TYPE_CODE]         = $product->{Product::FIELD_TAX_TYPE}->code;
        $result[Api::PARAM_PROPERTIES]            = $this->regroupLanguageProperties(
            $product->{Product::FIELD_PROPERTIES},
            ProductProperties::FIELD_LANGUAGE,
            $this->getLanguageFilter()
        );

        return $result;
    }
}
