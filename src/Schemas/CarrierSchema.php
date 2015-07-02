<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Region;
use \Neomerx\Core\Models\Carrier;
use \Neomerx\Core\Models\Country;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\CustomerType;
use \Neomerx\Core\Models\CarrierPostcode;
use \Neomerx\Core\Models\CarrierTerritory;
use \Neomerx\JsonApi\Schema\SchemaProvider;
use \Neomerx\Core\Models\CarrierProperties;
use \Neomerx\Core\Models\CarrierCustomerType;

/**
 * @package Neomerx\CoreApi
 */
class CarrierSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'carriers';

    /** Resources sub-URL */
    const SUB_URL = '/carriers/';

    /** Schema attribute */
    const ATTR_CODE = Carrier::FIELD_CODE;

    /** Schema attribute */
    const ATTR_DATA = Carrier::FIELD_DATA;

    /** Schema attribute */
    const ATTR_SETTINGS = Carrier::FIELD_SETTINGS;

    /** Schema attribute */
    const ATTR_CALCULATOR_CODE = Carrier::FIELD_CALCULATOR_CODE;

    /** Schema attribute */
    const ATTR_IS_TAXABLE = Carrier::FIELD_IS_TAXABLE;

    /** Schema attribute */
    const ATTR_MAX_COST = Carrier::FIELD_MAX_COST;

    /** Schema attribute */
    const ATTR_MAX_DIMENSION = Carrier::FIELD_MAX_DIMENSION;

    /** Schema attribute */
    const ATTR_MAX_WEIGHT = Carrier::FIELD_MAX_WEIGHT;

    /** Schema attribute */
    const ATTR_MIN_COST = Carrier::FIELD_MIN_COST;

    /** Schema attribute */
    const ATTR_MIN_DIMENSION = Carrier::FIELD_MIN_DIMENSION;

    /** Schema attribute */
    const ATTR_MIN_WEIGHT = Carrier::FIELD_MIN_WEIGHT;

    /** Schema attribute */
    const ATTR_PROPERTIES = Carrier::FIELD_PROPERTIES;

    /** Schema attribute */
    const ATTR_CREATED_AT = Carrier::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = Carrier::FIELD_UPDATED_AT;

    /** Schema attribute */
    const ATTR_TERRITORIES = Carrier::FIELD_TERRITORIES;

    /** Schema attribute */
    const ATTR_TERRITORIES_COUNTRIES = CountrySchema::TYPE;

    /** Schema attribute */
    const ATTR_TERRITORIES_REGIONS = RegionSchema::TYPE;

    /** Schema attribute */
    const ATTR_POSTCODES = Carrier::FIELD_POSTCODES;

    /** Schema attribute */
    const ATTR_POSTCODES_FROM = CarrierPostcode::FIELD_POSTCODE_FROM;

    /** Schema attribute */
    const ATTR_POSTCODES_TO = CarrierPostcode::FIELD_POSTCODE_TO;

    /** Schema attribute */
    const ATTR_POSTCODES_MASK = CarrierPostcode::FIELD_POSTCODE_MASK;

    /** Schema attribute */
    const ATTR_CUSTOMER_TYPES = CustomerTypeSchema::TYPE;

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
    public function getId($carrier)
    {
        /** @var Carrier $carrier */
        return $carrier->{Carrier::FIELD_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($carrier)
    {
        $properties = [];
        foreach ($carrier->{Carrier::FIELD_PROPERTIES} as $property) {
            $isoCode = $property->{CarrierProperties::FIELD_LANGUAGE}->{Language::FIELD_ISO_CODE};
            $properties[$isoCode] = [
                CarrierProperties::FIELD_NAME        => $property->{CarrierProperties::FIELD_NAME},
                CarrierProperties::FIELD_DESCRIPTION => $property->{CarrierProperties::FIELD_DESCRIPTION},
            ];
        }

        $territories = [];
        foreach ($carrier->{Carrier::FIELD_TERRITORIES} as $carrierTerritory) {
            /** @var CarrierTerritory $carrierTerritory */
            $territory = $carrierTerritory->{CarrierTerritory::FIELD_TERRITORY};
            list($type, $codeFieldName) = S\arrayGetValueEx([
                Country::class => [self::ATTR_TERRITORIES_COUNTRIES, Country::FIELD_CODE],
                Region::class  => [self::ATTR_TERRITORIES_REGIONS, Region::FIELD_CODE],
            ], get_class($territory));

            $territoryCode = $territory->{$codeFieldName};

            $territories[$type][] = $territoryCode;
        }

        $postcodes = [];
        foreach ($carrier->{Carrier::FIELD_POSTCODES} as $postcode) {
            /** @var CarrierPostcode $postcode */
            $postcodes[] = [
                self::ATTR_POSTCODES_FROM => $postcode->{CarrierPostcode::FIELD_POSTCODE_FROM},
                self::ATTR_POSTCODES_TO   => $postcode->{CarrierPostcode::FIELD_POSTCODE_TO},
                self::ATTR_POSTCODES_MASK => $postcode->{CarrierPostcode::FIELD_POSTCODE_MASK},
            ];
        }

        $customerTypes = [];
        foreach ($carrier->{Carrier::FIELD_CUSTOMER_TYPES} as $carrierCustomerType) {
            /** @var CarrierCustomerType $carrierCustomerType */
            /** @var CustomerType $customerType */
            $customerType = $carrierCustomerType->{CarrierCustomerType::FIELD_TYPE};
            $customerTypes[] = $customerType->{CustomerType::FIELD_CODE};
        }

        /** @var Carrier $carrier */
        return [
            self::ATTR_DATA            => $carrier->{Carrier::FIELD_DATA},
            self::ATTR_SETTINGS        => $carrier->{Carrier::FIELD_SETTINGS},
            self::ATTR_CALCULATOR_CODE => $carrier->{Carrier::FIELD_CALCULATOR_CODE},
            self::ATTR_IS_TAXABLE      => $carrier->{Carrier::FIELD_IS_TAXABLE},
            self::ATTR_MAX_COST        => $carrier->{Carrier::FIELD_MAX_COST},
            self::ATTR_MAX_DIMENSION   => $carrier->{Carrier::FIELD_MAX_DIMENSION},
            self::ATTR_MAX_WEIGHT      => $carrier->{Carrier::FIELD_MAX_WEIGHT},
            self::ATTR_MIN_COST        => $carrier->{Carrier::FIELD_MIN_COST},
            self::ATTR_MIN_DIMENSION   => $carrier->{Carrier::FIELD_MIN_DIMENSION},
            self::ATTR_MIN_WEIGHT      => $carrier->{Carrier::FIELD_MIN_WEIGHT},
            self::ATTR_TERRITORIES     => $territories,
            self::ATTR_POSTCODES       => $postcodes,
            self::ATTR_CUSTOMER_TYPES  => $customerTypes,
            self::ATTR_PROPERTIES      => $properties,
            self::ATTR_CREATED_AT      => $carrier->{Carrier::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT      => $carrier->{Carrier::FIELD_UPDATED_AT},
        ];
    }
}
