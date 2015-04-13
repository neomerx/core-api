<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\CustomerRisk;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

class CustomerRiskConverterGeneric implements ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param CustomerRisk $customerRisk
     *
     * @return array
     */
    public function convert($customerRisk = null)
    {
        if ($customerRisk === null) {
            return null;
        }

        ($customerRisk instanceof CustomerRisk) ?: S\throwEx(new InvalidArgumentException('customerRisk'));

        return $customerRisk->attributesToArray();
    }
}
