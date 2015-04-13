<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Address;
use \Neomerx\CoreApi\Api\Addresses\AddressesInterface;
use \Neomerx\CoreApi\Api\Territories\RegionsInterface;

class AddressConverterGeneric implements ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param Address $address
     *
     * @return null|array<mixed,mixed>
     */
    public function convert($address = null)
    {
        if ($address === null) {
            return null;
        }

        assert('$address instanceof '.Address::class);

        $region = $address->region;
        $result = array_merge($address->attributesToArray(), [
            AddressesInterface::PARAM_REGION_CODE => ($region === null ? null : $region->code),
            RegionsInterface::PARAM_COUNTRY_CODE  => ($region === null ? null : $region->country->code),
        ]);

        return $result;
    }
}
