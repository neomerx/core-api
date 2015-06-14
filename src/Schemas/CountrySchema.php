<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Country;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\CountryProperties;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class CountrySchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'countries';

    /** Resources sub-URL */
    const SUB_URL = '/countries/';

    /** Schema attribute */
    const ATTR_PROPERTIES = Country::FIELD_PROPERTIES;

    /** Schema attribute */
    const ATTR_CREATED_AT = Country::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = Country::FIELD_UPDATED_AT;

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
    public function getId($country)
    {
        /** @var Country $country */
        return $country->{Country::FIELD_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($country)
    {
        $attributes = [];
        foreach ($country->{Country::FIELD_PROPERTIES} as $property) {
            $isoCode = $property->{CountryProperties::FIELD_LANGUAGE}->{Language::FIELD_ISO_CODE};
            $attributes[$isoCode] = [
                CountryProperties::FIELD_NAME        => $property->{CountryProperties::FIELD_NAME},
                CountryProperties::FIELD_DESCRIPTION => $property->{CountryProperties::FIELD_DESCRIPTION},
            ];
        }

        /** @var Country $country */
        return [
            self::ATTR_PROPERTIES => $attributes,
            self::ATTR_CREATED_AT => $country->{Country::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT => $country->{Country::FIELD_UPDATED_AT},
        ];
    }
}
