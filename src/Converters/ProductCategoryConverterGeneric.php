<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Product;
use \Neomerx\Core\Models\Category;
use \Neomerx\Core\Models\ProductCategory;
use \Neomerx\CoreApi\Api\Products\ProductCategoriesInterface as Api;

class ProductCategoryConverterGeneric implements ConverterInterface
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param ProductCategory $category
     *
     * @return array
     */
    public function convert($category = null)
    {
        if ($category === null) {
            return null;
        }

        assert('$category instanceof '.ProductCategory::class);

        /** @var ProductCategory $category */

        return [
            Api::PARAM_ID            => $category->{ProductCategory::FIELD_ID},
            Api::PARAM_PRODUCT_SKU   => $category->{ProductCategory::FIELD_PRODUCT}->{Product::FIELD_SKU},
            Api::PARAM_CATEGORY_CODE => $category->{ProductCategory::FIELD_CATEGORY}->{Category::FIELD_CODE},
            Api::PARAM_POSITION      => $category->{ProductCategory::FIELD_POSITION},
        ];
    }
}
