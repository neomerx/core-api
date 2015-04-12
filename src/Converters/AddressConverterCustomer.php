<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\CustomerAddress;
use \Neomerx\Core\Exceptions\InvalidArgumentException;

class AddressConverterCustomer extends AddressConverterGeneric
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param CustomerAddress $customerAddress
     *
     * @return null|array<mixed,mixed>
     */
    public function convert($customerAddress = null)
    {
        if ($customerAddress === null) {
            return null;
        }

        ($customerAddress instanceof CustomerAddress) ?: S\throwEx(new InvalidArgumentException('customerAddress'));

        $result = array_merge(parent::convert($customerAddress->address), $customerAddress->attributesToArray());

        return $result;
    }
}
