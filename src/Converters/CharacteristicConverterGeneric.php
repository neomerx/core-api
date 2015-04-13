<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Characteristic;
use \Neomerx\Core\Models\CharacteristicProperties;
use \Neomerx\Core\Exceptions\InvalidArgumentException;
use \Neomerx\CoreApi\Api\Features\CharacteristicsInterface as Api;

class CharacteristicConverterGeneric extends BasicConverterWithLanguageFilter
{
    use LanguagePropertiesTrait;

    /**
     * Format model to array representation.
     *
     * @param Characteristic $characteristic
     *
     * @return array
     */
    public function convert($characteristic = null)
    {
        if ($characteristic === null) {
            return null;
        }

        ($characteristic instanceof Characteristic) ?: S\throwEx(new InvalidArgumentException('characteristic'));

        $result = $characteristic->attributesToArray();

        $measurement = $characteristic->measurement;
        $result[Api::PARAM_MEASUREMENT_CODE] = $measurement === null ? null : $measurement->code;
        $result[Api::PARAM_PROPERTIES]       = $this->regroupLanguageProperties(
            $characteristic->properties,
            CharacteristicProperties::FIELD_LANGUAGE,
            $this->getLanguageFilter()
        );

        return $result;
    }
}
