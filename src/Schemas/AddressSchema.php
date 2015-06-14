<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Address;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class AddressSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'addresses';

    /** Resources sub-URL */
    const SUB_URL = '/addresses/';

    /** Schema attribute */
    const ATTR_ADDRESS1 = Address::FIELD_ADDRESS1;

    /** Schema attribute */
    const ATTR_ADDRESS2 = Address::FIELD_ADDRESS2;

    /** Schema attribute */
    const ATTR_CITY = Address::FIELD_CITY;

    /** Schema attribute */
    const ATTR_POSTCODE = Address::FIELD_POSTCODE;

    /** Schema attribute */
    const ATTR_CREATED_AT = Address::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = Address::FIELD_UPDATED_AT;

    /** Schema relationship */
    const REL_REGION = Address::FIELD_REGION;

    /**
     * @var string
     */
    protected $resourceType = self::TYPE;

    /**
     * @var string
     */
    protected $selfSubUrl = self::SUB_URL;

    /**
     * @inheritdoc
     */
    public function getId($address)
    {
        /** @var Address $address */
        return $address->{Address::FIELD_ID};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($address)
    {
        /** @var Address $address */
        return [
            self::ATTR_ADDRESS1   => $address->{Address::FIELD_ADDRESS1},
            self::ATTR_ADDRESS2   => $address->{Address::FIELD_ADDRESS2},
            self::ATTR_CITY       => $address->{Address::FIELD_CITY},
            self::ATTR_POSTCODE   => $address->{Address::FIELD_POSTCODE},
            self::ATTR_CREATED_AT => $address->{Address::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT => $address->{Address::FIELD_UPDATED_AT},
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($address)
    {
        /** @var Address $address */
        return [self::REL_REGION => [self::DATA => $address->{Address::FIELD_REGION}]];
    }
}
