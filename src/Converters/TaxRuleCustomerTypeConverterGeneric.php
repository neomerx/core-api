<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\CustomerType;
use \Neomerx\Core\Models\TaxRuleCustomerType as Model;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleCustomerTypesInterface as Api;

class TaxRuleCustomerTypeConverterGeneric extends BasicConverterWithLanguageFilter
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
        $result[Api::PARAM_TYPE_CODE] = $resource->{Model::FIELD_TYPE}->{CustomerType::FIELD_CODE};

        return $result;
    }
}
