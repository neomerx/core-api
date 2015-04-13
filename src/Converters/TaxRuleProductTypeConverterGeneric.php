<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\ProductTaxType;
use \Neomerx\Core\Models\TaxRuleProductType as Model;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleProductTypesInterface as Api;

class TaxRuleProductTypeConverterGeneric extends BasicConverterWithLanguageFilter
{
    /**
     * Format model to array representation.
     *
     * @param Model $resource
     *
     * @return array
     */
    public function convert($resource = null)
    {
        if ($resource === null) {
            return null;
        }

        assert('$resource instanceof '.Model::class);

        $result                       = $resource->attributesToArray();
        $result[Api::PARAM_TYPE_CODE] = $resource->{Model::FIELD_TYPE}->{ProductTaxType::FIELD_CODE};

        return $result;
    }
}
