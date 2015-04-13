<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Carrier;
use \Neomerx\Core\Models\CarrierProperties;
use \Neomerx\CoreApi\Api\Carriers\CarriersInterface as Api;

class CarrierConverterGeneric extends BasicConverterWithLanguageFilter
{
    use LanguagePropertiesTrait;

    /**
     * Format model to array representation.
     *
     * @param Carrier $carrier
     *
     * @return array
     */
    public function convert($carrier = null)
    {
        if ($carrier === null) {
            return null;
        }

        assert('$carrier instanceof '.Carrier::class);

        $result = $carrier->attributesToArray();

        $result[Api::PARAM_PROPERTIES] = $this->regroupLanguageProperties(
            $carrier->properties,
            CarrierProperties::FIELD_LANGUAGE,
            $this->getLanguageFilter()
        );

        return $result;
    }
}
