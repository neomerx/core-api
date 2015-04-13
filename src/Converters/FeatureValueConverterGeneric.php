<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\CharacteristicValue;
use \Neomerx\CoreApi\Api\Features\FeatureValuesInterface as Api;
use \Neomerx\Core\Exceptions\InvalidArgumentException;
use \Neomerx\Core\Models\CharacteristicValueProperties;

class FeatureValueConverterGeneric extends BasicConverterWithLanguageFilter
{
    use LanguagePropertiesTrait;

    /**
     * Format model to array representation.
     *
     * @param CharacteristicValue $charValue
     *
     * @return array
     */
    public function convert($charValue = null)
    {
        if ($charValue === null) {
            return null;
        }

        ($charValue instanceof CharacteristicValue) ?: S\throwEx(new InvalidArgumentException('charValue'));

        $result = $charValue->attributesToArray();

        $result[Api::PARAM_CHARACTERISTIC_CODE] = $charValue->characteristic->code;
        $result[Api::PARAM_PROPERTIES]          = $this->regroupLanguageProperties(
            $charValue->properties,
            CharacteristicValueProperties::FIELD_LANGUAGE,
            $this->getLanguageFilter()
        );

        return $result;
    }
}
