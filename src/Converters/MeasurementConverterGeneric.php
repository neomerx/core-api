<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Measurement;
use \Neomerx\Core\Models\MeasurementProperties;
use \Neomerx\Core\Exceptions\InvalidArgumentException;
use \Neomerx\CoreApi\Api\Features\MeasurementsInterface as Api;

class MeasurementConverterGeneric extends BasicConverterWithLanguageFilter
{
    use LanguagePropertiesTrait;

    /**
     * Format model to array representation.
     *
     * @param Measurement $measurement
     *
     * @return array
     */
    public function convert($measurement = null)
    {
        if ($measurement === null) {
            return null;
        }

        ($measurement instanceof Measurement) ?: S\throwEx(new InvalidArgumentException('measurement'));

        $result = $measurement->attributesToArray();

        $result[Api::PARAM_PROPERTIES] = $this->regroupLanguageProperties(
            $measurement->properties,
            MeasurementProperties::FIELD_LANGUAGE,
            $this->getLanguageFilter()
        );

        return $result;
    }
}
