<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Tax;
use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Region;
use \Neomerx\Core\Models\TaxRule;
use \Neomerx\Core\Models\Country;
use \Neomerx\Core\Models\CustomerType;
use \Neomerx\Core\Models\ProductTaxType;
use \Neomerx\Core\Models\TaxRulePostcode;
use \Neomerx\Core\Models\TaxRuleTerritory;
use \Neomerx\JsonApi\Schema\SchemaProvider;
use \Neomerx\Core\Models\TaxRuleProductType;
use \Neomerx\Core\Models\TaxRuleCustomerType;

/**
 * @package Neomerx\CoreApi
 */
class TaxRuleSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'tax-rules';

    /** Resources sub-URL */
    const SUB_URL = '/tax-rules/';

    /** Schema attribute */
    const ATTR_ID = TaxRule::FIELD_ID;

    /** Schema attribute */
    const ATTR_NAME = TaxRule::FIELD_NAME;

    /** Schema attribute */
    const ATTR_PRIORITY = TaxRule::FIELD_PRIORITY;

    /** Schema attribute */
    const ATTR_CREATED_AT = TaxRule::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = TaxRule::FIELD_UPDATED_AT;

    /** Schema attribute */
    const ATTR_TERRITORIES = TaxRule::FIELD_TERRITORIES;

    /** Schema attribute */
    const ATTR_TERRITORIES_COUNTRIES = CountrySchema::TYPE;

    /** Schema attribute */
    const ATTR_TERRITORIES_REGIONS = RegionSchema::TYPE;

    /** Schema attribute */
    const ATTR_POSTCODES = TaxRule::FIELD_POSTCODES;

    /** Schema attribute */
    const ATTR_POSTCODES_FROM = TaxRulePostcode::FIELD_POSTCODE_FROM;

    /** Schema attribute */
    const ATTR_POSTCODES_TO = TaxRulePostcode::FIELD_POSTCODE_TO;

    /** Schema attribute */
    const ATTR_POSTCODES_MASK = TaxRulePostcode::FIELD_POSTCODE_MASK;

    /** Schema attribute */
    const ATTR_CUSTOMER_TYPES = CustomerTypeSchema::TYPE;

    /** Schema attribute */
    const ATTR_PRODUCT_TAX_TYPES = ProductTaxTypeSchema::TYPE;

    /** Schema relationship */
    const REL_TAX = TaxRule::FIELD_TAX;

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
    public function getId($taxRule)
    {
        /** @var TaxRule $taxRule */
        return $taxRule->{TaxRule::FIELD_ID};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($taxRule)
    {
        /** @var TaxRule $taxRule */

        $territories = [];
        foreach ($taxRule->{TaxRule::FIELD_TERRITORIES} as $taxRuleTerritory) {
            /** @var TaxRuleTerritory $taxRuleTerritory */
            $territory = $taxRuleTerritory->{TaxRuleTerritory::FIELD_TERRITORY};
            list($type, $codeFieldName) = S\arrayGetValueEx([
                Country::class => [self::ATTR_TERRITORIES_COUNTRIES, Country::FIELD_CODE],
                Region::class  => [self::ATTR_TERRITORIES_REGIONS, Region::FIELD_CODE],
            ], get_class($territory));

            $territoryCode = $territory->{$codeFieldName};

            $territories[$type][] = $territoryCode;
        }

        $postcodes = [];
        foreach ($taxRule->{TaxRule::FIELD_POSTCODES} as $postcode) {
            /** @var TaxRulePostcode $postcode */
            $postcodes[] = [
                self::ATTR_POSTCODES_FROM => $postcode->{TaxRulePostcode::FIELD_POSTCODE_FROM},
                self::ATTR_POSTCODES_TO   => $postcode->{TaxRulePostcode::FIELD_POSTCODE_TO},
                self::ATTR_POSTCODES_MASK => $postcode->{TaxRulePostcode::FIELD_POSTCODE_MASK},
            ];
        }

        $customerTypes = [];
        foreach ($taxRule->{TaxRule::FIELD_CUSTOMER_TYPES} as $taxRuleCustomerType) {
            /** @var TaxRuleCustomerType $taxRuleCustomerType */
            /** @var CustomerType $customerType */
            $customerType = $taxRuleCustomerType->{TaxRuleCustomerType::FIELD_TYPE};
            $customerTypes[] = $customerType->{CustomerType::FIELD_CODE};
        }

        $productTaxTypes = [];
        foreach ($taxRule->{TaxRule::FIELD_PRODUCT_TYPES} as $ruleTaxType) {
            /** @var TaxRuleProductType $ruleTaxType */
            /** @var ProductTaxType $taxType */
            $taxType = $ruleTaxType->{TaxRuleProductType::FIELD_TYPE};
            $productTaxTypes[] = $taxType->{ProductTaxType::FIELD_CODE};
        }

        /** @var TaxRule $taxRule */
        return [
            self::ATTR_NAME              => $taxRule->{TaxRule::FIELD_NAME},
            self::ATTR_PRIORITY          => $taxRule->{TaxRule::FIELD_PRIORITY},
            self::ATTR_TERRITORIES       => $territories,
            self::ATTR_POSTCODES         => $postcodes,
            self::ATTR_CUSTOMER_TYPES    => $customerTypes,
            self::ATTR_PRODUCT_TAX_TYPES => $productTaxTypes,
            self::ATTR_CREATED_AT        => $taxRule->{TaxRule::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT        => $taxRule->{TaxRule::FIELD_UPDATED_AT},
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($tax)
    {
        /** @var Tax $tax */
        return [
            self::REL_TAX => [
                self::DATA => $tax->{TaxRule::FIELD_TAX},
            ],
        ];
    }
}
