<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\Variant;
use \Neomerx\Core\Models\Specification;
use \Neomerx\Core\Models\CharacteristicValue;
use \Neomerx\CoreApi\Api\Products\SpecificationsInterface as Api;

class SpecificationConverterGeneric implements ConverterInterface
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param Specification $specification
     *
     * @return array
     */
    public function convert($specification = null)
    {
        if ($specification === null) {
            return null;
        }

        assert('$specification instanceof '.Specification::class);

        /** @var Specification $specification */

        $variant = $specification->{Specification::FIELD_VARIANT};
        return [
            Api::PARAM_ID          => $specification->{Specification::FIELD_ID},
            Api::PARAM_PRODUCT_SKU => $specification->{Specification::FIELD_PRODUCT}->{Product::FIELD_SKU},
            Api::PARAM_VARIANT_SKU => $variant === null ? null : $variant->{Variant::FIELD_SKU},
            Api::PARAM_VALUE_CODE  => $specification->{Specification::FIELD_VALUE}->{CharacteristicValue::FIELD_CODE},
            Api::PARAM_POSITION    => $specification->{Specification::FIELD_POSITION},
        ];
    }
}
