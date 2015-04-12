<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Carrier;
use \Neomerx\Core\Models\CarrierTerritory;
use \Neomerx\CoreApi\Api\Carriers\CarrierTerritories as Api;

class CarrierTerritoryConverterGeneric extends BasicConverterWithLanguageFilter
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param CarrierTerritory $resource
     *
     * @return array
     */
    public function convert($resource = null)
    {
        if ($resource === null) {
            return null;
        }

        assert('$resource instanceof '.CarrierTerritory::class);

        $result = [];

        $result[Api::PARAM_CARRIER_CODE]   = $resource->{CarrierTerritory::FIELD_CARRIER}->{Carrier::FIELD_CODE};
        $result[Api::PARAM_TERRITORY_CODE] = $resource->{CarrierTerritory::FIELD_TERRITORY_ID};
        $result[Api::PARAM_TERRITORY_TYPE] = S\arrayGetValueEx([
            CarrierTerritory::TERRITORY_TYPE_REGION  => Api::TERRITORY_TYPE_REGION,
            CarrierTerritory::TERRITORY_TYPE_COUNTRY => Api::TERRITORY_TYPE_COUNTRY,
        ], $resource->{CarrierTerritory::FIELD_TERRITORY_TYPE});

        return $result;
    }
}
