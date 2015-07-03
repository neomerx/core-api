<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \DB;
use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\TaxRule;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\CoreApi\Schemas\TaxSchema;
use \Neomerx\CoreApi\Api\Facades\TaxRules;
use \Neomerx\CoreApi\Schemas\TaxRuleSchema;
use \Neomerx\CoreApi\Api\Taxes\TaxRulesInterface;
use \Neomerx\CoreApi\Api\Facades\TaxRulePostcodes;
use \Neomerx\CoreApi\Api\Facades\TaxRuleTerritories;
use \Neomerx\CoreApi\Api\Facades\TaxRuleProductTypes;
use \Neomerx\CoreApi\Api\Facades\TaxRuleCustomerTypes;
use \Neomerx\CoreApi\Api\Taxes\TaxRulePostcodesInterface;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleTerritoriesInterface;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleProductTypesInterface;
use \Neomerx\CoreApi\Api\Taxes\TaxRuleCustomerTypesInterface;

/**
 * @package Neomerx\CoreApi
 */
final class TaxRulesControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(TaxRules::INTERFACE_BIND_NAME);
    }

    /**
     * Create a carrier.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, , $attributes, $relationships) = $this->parseDocumentAsSingleData(TaxRuleSchema::TYPE, [
            TaxRuleSchema::REL_TAX => TaxSchema::TYPE,
        ]);

        $territories   = S\arrayGetValue($attributes, TaxRuleSchema::ATTR_TERRITORIES, []);
        $postcodes     = S\arrayGetValue($attributes, TaxRuleSchema::ATTR_POSTCODES, []);
        $customerTypes = S\arrayGetValue($attributes, TaxRuleSchema::ATTR_CUSTOMER_TYPES, []);
        $productTypes  = S\arrayGetValue($attributes, TaxRuleSchema::ATTR_PRODUCT_TAX_TYPES, []);

        $attributes[TaxRulesInterface::PARAM_TAX_CODE] = S\arrayGetValue($relationships, TaxRuleSchema::REL_TAX);

        DB::beginTransaction();
        try {
            /** @var TaxRule $resource */
            $resource = $this->getApiFacade()->create($attributes);
            $resourceId = $resource->{TaxRule::FIELD_ID};

            $this->createTerritories($territories, $resourceId);
            $this->createPostcodes($postcodes, $resourceId);
            $this->createCustomerTypes($customerTypes, $resourceId);
            $this->createProductTypes($productTypes, $resourceId);

            $allExecutedOk = true;

        } finally {
            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
        }

        return $this->getCreatedResponse($this->getApiFacade()->read($resourceId));
    }

    /**
     * Update carrier.
     *
     * @param string $resourceCode
     *
     * @return Response
     */
    final public function update($resourceCode)
    {
        $this->checkParametersEmpty();

        list(, , $attributes, $relationships) = $this->parseDocumentAsSingleData(TaxRuleSchema::TYPE, [
            TaxRuleSchema::REL_TAX => TaxSchema::TYPE,
        ]);

        $territories   = S\arrayGetValue($attributes, TaxRuleSchema::ATTR_TERRITORIES);
        $postcodes     = S\arrayGetValue($attributes, TaxRuleSchema::ATTR_POSTCODES);
        $customerTypes = S\arrayGetValue($attributes, TaxRuleSchema::ATTR_CUSTOMER_TYPES);
        $productTypes  = S\arrayGetValue($attributes, TaxRuleSchema::ATTR_PRODUCT_TAX_TYPES);

        if (($taxCode = S\arrayGetValue($relationships, TaxRuleSchema::REL_TAX)) !== null) {
            $attributes[TaxRulesInterface::PARAM_TAX_CODE] = $taxCode;
        }

        DB::beginTransaction();
        try {
            $carrier = $this->getApiFacade()->read($resourceCode);

            $this->getApiFacade()->update($resourceCode, $attributes);

            if ($territories !== null) {
                $this->deleteTerritories($carrier);
                $this->createTerritories($territories, $resourceCode);
            }

            if ($postcodes !== null) {
                $this->deletePostcodes($carrier);
                $this->createPostcodes($postcodes, $resourceCode);
            }

            if ($customerTypes !== null) {
                $this->deleteCustomerTypes($carrier);
                $this->createCustomerTypes($customerTypes, $resourceCode);
            }

            if ($productTypes !== null) {
                $this->deleteProductTypes($carrier);
                $this->createProductTypes($productTypes, $resourceCode);
            }

            $allExecutedOk = true;

        } finally {
            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
        }

        return $this->getContentResponse($this->getApiFacade()->read($resourceCode));
    }

    /**
     * @param array  $territories
     * @param string $ruleId
     *
     * @return void
     */
    private function createTerritories(array $territories, $ruleId)
    {
        $countries = S\arrayGetValue($territories, TaxRuleSchema::ATTR_TERRITORIES_COUNTRIES, []);
        $regions   = S\arrayGetValue($territories, TaxRuleSchema::ATTR_TERRITORIES_REGIONS, []);

        $typeCountry = TaxRuleTerritoriesInterface::TERRITORY_TYPE_COUNTRY;
        foreach ($countries as $countryCode) {
            TaxRuleTerritories::create([
                TaxRuleTerritoriesInterface::PARAM_ID_RULE        => $ruleId,
                TaxRuleTerritoriesInterface::PARAM_TERRITORY_CODE => $countryCode,
                TaxRuleTerritoriesInterface::PARAM_TERRITORY_TYPE => $typeCountry,
            ]);
        }

        $typeRegion = TaxRuleTerritoriesInterface::TERRITORY_TYPE_REGION;
        foreach ($regions as $regionCode) {
            TaxRuleTerritories::create([
                TaxRuleTerritoriesInterface::PARAM_ID_RULE        => $ruleId,
                TaxRuleTerritoriesInterface::PARAM_TERRITORY_CODE => $regionCode,
                TaxRuleTerritoriesInterface::PARAM_TERRITORY_TYPE => $typeRegion,
            ]);
        }
    }

    /**
     * @param array  $postcodes
     * @param string $ruleId
     *
     * @return void
     */
    private function createPostcodes(array $postcodes, $ruleId)
    {
        foreach ($postcodes as $postcode) {
            $from = S\arrayGetValue($postcode, TaxRuleSchema::ATTR_POSTCODES_FROM);
            $to   = S\arrayGetValue($postcode, TaxRuleSchema::ATTR_POSTCODES_TO);
            $mask = S\arrayGetValue($postcode, TaxRuleSchema::ATTR_POSTCODES_MASK);

            TaxRulePostcodes::create(S\arrayFilterNulls([
                TaxRulePostcodesInterface::PARAM_ID_RULE       => $ruleId,
                TaxRulePostcodesInterface::PARAM_POSTCODE_FROM => $from,
                TaxRulePostcodesInterface::PARAM_POSTCODE_TO   => $to,
                TaxRulePostcodesInterface::PARAM_POSTCODE_MASK => $mask,
            ]));
        }
    }

    /**
     * @param array  $customerTypes
     * @param string $ruleId
     *
     * @return void
     */
    private function createCustomerTypes(array $customerTypes, $ruleId)
    {
        foreach ($customerTypes as $customerTypeCode) {
            TaxRuleCustomerTypes::create(S\arrayFilterNulls([
                TaxRuleCustomerTypesInterface::PARAM_ID_RULE   => $ruleId,
                TaxRuleCustomerTypesInterface::PARAM_TYPE_CODE => $customerTypeCode,
            ]));
        }
    }

    /**
     * @param array  $productTypes
     * @param string $ruleId
     *
     * @return void
     */
    private function createProductTypes(array $productTypes, $ruleId)
    {
        foreach ($productTypes as $productType) {
            TaxRuleProductTypes::create(S\arrayFilterNulls([
                TaxRuleProductTypesInterface::PARAM_ID_RULE   => $ruleId,
                TaxRuleProductTypesInterface::PARAM_TYPE_CODE => $productType,
            ]));
        }
    }

    /**
     * @param TaxRule $rule
     *
     * @return void
     */
    private function deleteTerritories(TaxRule $rule)
    {
        foreach ($rule->{TaxRule::FIELD_TERRITORIES} as $territory) {
            /** @var BaseModel $territory */
            $territory->deleteOrFail();
        }
    }

    /**
     * @param TaxRule $rule
     *
     * @return void
     */
    private function deletePostcodes(TaxRule $rule)
    {
        foreach ($rule->{TaxRule::FIELD_POSTCODES} as $postcode) {
            /** @var BaseModel $postcode */
            $postcode->deleteOrFail();
        }
    }

    /**
     * @param TaxRule $rule
     *
     * @return void
     */
    private function deleteCustomerTypes(TaxRule $rule)
    {
        foreach ($rule->{TaxRule::FIELD_CUSTOMER_TYPES} as $customerType) {
            /** @var BaseModel $customerType */
            $customerType->deleteOrFail();
        }
    }

    /**
     * @param TaxRule $rule
     *
     * @return void
     */
    private function deleteProductTypes(TaxRule $rule)
    {
        foreach ($rule->{TaxRule::FIELD_PRODUCT_TYPES} as $productType) {
            /** @var BaseModel $productType */
            $productType->deleteOrFail();
        }
    }
}
