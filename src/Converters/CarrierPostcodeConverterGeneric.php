<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Carrier;
use \Neomerx\Core\Models\CarrierPostcode;
use \Neomerx\CoreApi\Api\Carriers\CarrierPostcodes as Api;

class CarrierPostcodeConverterGeneric extends BasicConverterWithLanguageFilter
{
    /**
     * Format model to array representation.
     *
     * @param CarrierPostcode $resource
     *
     * @return array
     */
    public function convert($resource = null)
    {
        if ($resource === null) {
            return null;
        }

        assert('$resource instanceof '.CarrierPostcode::class);

        $result = [];
        $result[Api::PARAM_CARRIER_CODE] = $resource->{CarrierPostcode::FIELD_CARRIER}->{Carrier::FIELD_CODE};

        $result = array_merge($result, $resource->attributesToArray());

        return $result;
    }
}
