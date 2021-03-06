<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Currency;
use \Neomerx\Core\Models\CurrencyProperties;
use \Neomerx\Core\Exceptions\InvalidArgumentException;
use \Neomerx\CoreApi\Api\Currencies\CurrenciesInterface as Api;

class CurrencyConverterGeneric extends BasicConverterWithLanguageFilter
{
    use LanguagePropertiesTrait;

    /**
     * Format model to array representation.
     *
     * @param Currency $currency
     *
     * @return array<mixed,mixed>
     */
    public function convert($currency = null)
    {
        if ($currency === null) {
            return null;
        }

        ($currency instanceof Currency) ?: S\throwEx(new InvalidArgumentException('currency'));

        $result = $currency->attributesToArray();

        $result[Api::PARAM_PROPERTIES] = $this->regroupLanguageProperties(
            $currency->properties,
            CurrencyProperties::FIELD_LANGUAGE,
            $this->getLanguageFilter()
        );

        return $result;
    }
}
