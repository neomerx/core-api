<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Carrier;
use \Neomerx\Core\Models\CustomerType;
use \Neomerx\Core\Models\CarrierCustomerType;
use \Neomerx\CoreApi\Api\Carriers\CarrierCustomerTypes as Api;

class CarrierCustomerTypeConverterGeneric extends BasicConverterWithLanguageFilter
{
    /**
     * Format model to array representation.
     *
     * @param CarrierCustomerType $resource
     *
     * @return array
     */
    public function convert($resource = null)
    {
        if ($resource === null) {
            return null;
        }

        assert('$resource instanceof '.CarrierCustomerType::class);

        $result = [];

        $result[Api::PARAM_CARRIER_CODE] = $resource->{CarrierCustomerType::FIELD_CARRIER}->{Carrier::FIELD_CODE};

        $type = $resource->{CarrierCustomerType::FIELD_TYPE};
        $result[Api::PARAM_TYPE_CODE] = $type !== null? $type->{CustomerType::FIELD_CODE} : Api::ALL_TYPE_CODES;

        return $result;
    }
}
