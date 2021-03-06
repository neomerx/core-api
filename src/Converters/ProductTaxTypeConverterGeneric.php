<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\ProductTaxType;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

class ProductTaxTypeConverterGeneric implements ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param ProductTaxType $productTaxType
     *
     * @return array
     */
    public function convert($productTaxType = null)
    {
        if ($productTaxType === null) {
            return null;
        }

        ($productTaxType instanceof ProductTaxType) ?: S\throwEx(new InvalidArgumentException('productTaxType'));

        return $productTaxType->attributesToArray();
    }
}
