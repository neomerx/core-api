<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\VariantProperties;
use \Neomerx\CoreApi\Api\Products\VariantsInterface as Api;

class VariantConverterGeneric extends ProductConverterGeneric
{
    const BIND_NAME = __CLASS__;

    /**
     * @param Variant $variant
     *
     * @return array
     */
    public function convert($variant = null)
    {
        if ($variant === null) {
            return null;
        }

        assert('$variant instanceof '.Variant::class);

        $productConverted = parent::convert($variant->product);

        $variantConverted = $variant->attributesToArray();
        $variantConverted[Api::PARAM_PROPERTIES] = $this->regroupLanguageProperties(
            $variant->{Variant::FIELD_PROPERTIES},
            VariantProperties::FIELD_LANGUAGE,
            $this->getLanguageFilter()
        );

        return array_replace_recursive($productConverted, $variantConverted);
    }
}
