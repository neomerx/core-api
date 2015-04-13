<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\TaxRuleTerritory;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleTerritories as Api;

class TaxRuleTerritoryConverterGeneric extends BasicConverterWithLanguageFilter
{
    /**
     * Format model to array representation.
     *
     * @param TaxRuleTerritory $resource
     *
     * @return array
     */
    public function convert($resource = null)
    {
        if ($resource === null) {
            return null;
        }

        assert('$resource instanceof '.TaxRuleTerritory::class);

        $result = [];

        $result[Api::PARAM_ID_RULE]        = $resource->{TaxRuleTerritory::FIELD_ID_TAX_RULE};
        $result[Api::PARAM_TERRITORY_CODE] = $resource->{TaxRuleTerritory::FIELD_TERRITORY_ID};
        $result[Api::PARAM_TERRITORY_TYPE] = S\arrayGetValueEx([
            TaxRuleTerritory::TERRITORY_TYPE_REGION  => Api::TERRITORY_TYPE_REGION,
            TaxRuleTerritory::TERRITORY_TYPE_COUNTRY => Api::TERRITORY_TYPE_COUNTRY,
        ], $resource->{TaxRuleTerritory::FIELD_TERRITORY_TYPE});

        return $result;
    }
}
