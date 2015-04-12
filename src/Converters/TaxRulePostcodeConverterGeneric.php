<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\TaxRulePostcode;

class TaxRulePostcodeConverterGeneric extends BasicConverterWithLanguageFilter
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param TaxRulePostcode $resource
     *
     * @return array
     */
    public function convert($resource = null)
    {
        if ($resource === null) {
            return null;
        }

        assert('$resource instanceof '.TaxRulePostcode::class);

        $result = $resource->attributesToArray();
        return $result;
    }
}
