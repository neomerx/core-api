<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\ProductRelated;
use \Neomerx\CoreApi\Api\Products\RelatedInterface as Api;

class ProductRelatedConverterGeneric implements ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param ProductRelated $related
     *
     * @return array
     */
    public function convert($related = null)
    {
        if ($related === null) {
            return null;
        }

        assert('$related instanceof '.ProductRelated::class);

        /** @var ProductRelated $related */

        return [
            Api::PARAM_ID          => $related->{ProductRelated::FIELD_ID},
            Api::PARAM_PRODUCT_SKU => $related->{ProductRelated::FIELD_PRODUCT}->{Product::FIELD_SKU},
            Api::PARAM_RELATED_SKU => $related->{ProductRelated::FIELD_RELATED}->{Product::FIELD_SKU},
        ];
    }
}
