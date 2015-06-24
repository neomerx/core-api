<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\Manufacturer;
use \Neomerx\JsonApi\Schema\SchemaProvider;
use \Neomerx\Core\Models\ManufacturerProperties;

/**
 * @package Neomerx\CoreApi
 */
class ManufacturerSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'manufacturers';

    /** Resources sub-URL */
    const SUB_URL = '/manufacturers/';

    /** Schema attribute */
    const ATTR_PROPERTIES = Manufacturer::FIELD_PROPERTIES;

    /** Schema attribute */
    const ATTR_CREATED_AT = Manufacturer::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = Manufacturer::FIELD_UPDATED_AT;

    /** Schema relationship */
    const REL_ADDRESS = Manufacturer::FIELD_ADDRESS;

    /** Schema relationship */
    const REL_PRODUCTS = Manufacturer::FIELD_PRODUCTS;

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
    public function getId($manufacturer)
    {
        /** @var Manufacturer $manufacturer */
        return $manufacturer->{Manufacturer::FIELD_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($manufacturer)
    {
        $properties = [];
        foreach ($manufacturer->{Manufacturer::FIELD_PROPERTIES} as $property) {
            $isoCode = $property->{ManufacturerProperties::FIELD_LANGUAGE}->{Language::FIELD_ISO_CODE};
            $properties[$isoCode] = [
                ManufacturerProperties::FIELD_NAME        => $property->{ManufacturerProperties::FIELD_NAME},
                ManufacturerProperties::FIELD_DESCRIPTION => $property->{ManufacturerProperties::FIELD_DESCRIPTION},
            ];
        }

        /** @var Manufacturer $manufacturer */
        return [
            self::ATTR_PROPERTIES => $properties,
            self::ATTR_CREATED_AT => $manufacturer->{Manufacturer::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT => $manufacturer->{Manufacturer::FIELD_UPDATED_AT},
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($manufacturer)
    {
        /** @var Manufacturer $manufacturer */
        return [
            self::REL_ADDRESS => [self::DATA => $manufacturer->{Manufacturer::FIELD_ADDRESS}],

            // TODO add optional relationship to products
        ];
    }
}
