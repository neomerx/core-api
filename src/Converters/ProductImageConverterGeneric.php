<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\ProductImage;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

class ProductImageConverterGeneric extends ImageConverterGeneric
{
    /**
     * Format model to array representation.
     *
     * @param ProductImage $productImage
     *
     * @return array
     */
    public function convert($productImage = null)
    {
        if ($productImage === null) {
            return null;
        }

        ($productImage instanceof ProductImage) ?: S\throwEx(new InvalidArgumentException('productImage'));

        $result = array_merge($productImage->attributesToArray(), parent::convert($productImage->image));

        return $result;
    }
}
