<?php namespace Neomerx\CoreApi\Server\Http\Controllers\JsonApi;

use \DB;
use \Response;
use \Neomerx\Core\Support as S;
use \Neomerx\Core\Models\Carrier;
use \Neomerx\Core\Models\BaseModel;
use \Neomerx\CoreApi\Api\Facades\Carriers;
use \Neomerx\CoreApi\Schemas\CarrierSchema;
use \Neomerx\CoreApi\Api\Facades\CarrierPostcodes;
use \Neomerx\CoreApi\Api\Facades\CarrierTerritories;
use \Neomerx\CoreApi\Api\Carriers\CarriersInterface;
use \Neomerx\CoreApi\Api\Facades\CarrierCustomerTypes;
use \Neomerx\CoreApi\Api\Carriers\CarrierPostcodesInterface;
use \Neomerx\CoreApi\Api\Carriers\CarrierTerritoriesInterface;
use \Neomerx\CoreApi\Api\Carriers\CarrierCustomerTypesInterface;

/**
 * @package Neomerx\CoreApi
 */
final class CarriersControllerJson extends BaseJsonApiController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(Carriers::INTERFACE_BIND_NAME);
    }

    /**
     * Create a carrier.
     *
     * @return Response
     */
    final public function store()
    {
        $this->checkParametersEmpty();

        list(, $carrierCode, $attributes) = $this->parseDocumentAsSingleData(CarrierSchema::TYPE);

        $properties    = S\arrayGetValueEx($attributes, CarrierSchema::ATTR_PROPERTIES);
        $territories   = S\arrayGetValue($attributes, CarrierSchema::ATTR_TERRITORIES, []);
        $postcodes     = S\arrayGetValue($attributes, CarrierSchema::ATTR_POSTCODES, []);
        $customerTypes = S\arrayGetValue($attributes, CarrierSchema::ATTR_CUSTOMER_TYPES, []);

        $input = S\arrayFilterNulls(array_merge($attributes, [
            CarriersInterface::PARAM_CODE       => $carrierCode,
            CarriersInterface::PARAM_PROPERTIES => $properties,
        ]));

        DB::beginTransaction();
        try {
            /** @var Carrier $resource */
            $resource = $this->getApiFacade()->create($input);

            $this->createTerritories($territories, $carrierCode);
            $this->createPostcodes($postcodes, $carrierCode);
            $this->createCustomerTypes($customerTypes, $carrierCode);

            $allExecutedOk = true;

        } finally {
            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
        }

        return $this->getCreatedResponse($resource);
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

        list(, , $attributes) = $this->parseDocumentAsSingleData(CarrierSchema::TYPE);

        $properties    = S\arrayGetValue($attributes, CarrierSchema::ATTR_PROPERTIES);
        $territories   = S\arrayGetValue($attributes, CarrierSchema::ATTR_TERRITORIES);
        $postcodes     = S\arrayGetValue($attributes, CarrierSchema::ATTR_POSTCODES);
        $customerTypes = S\arrayGetValue($attributes, CarrierSchema::ATTR_CUSTOMER_TYPES);

        $input = S\arrayFilterNulls(array_merge($attributes, [
            CarriersInterface::PARAM_CODE       => $resourceCode,
            CarriersInterface::PARAM_PROPERTIES => $properties,
        ]));

        DB::beginTransaction();
        try {
            $carrier = $this->getApiFacade()->read($resourceCode);

            $this->getApiFacade()->update($resourceCode, $input);

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

            $allExecutedOk = true;

        } finally {
            isset($allExecutedOk) === true ? DB::commit() : DB::rollBack();
        }

        return $this->getContentResponse($this->getApiFacade()->read($resourceCode));
    }

    /**
     * @return Response
     */
    final public function showCalculators()
    {
        $this->checkParametersEmpty();

        $calculators = $this->getApiFacade()->getAvailableCalculators();
        return $this->getContentResponse($calculators);
    }

    /**
     * @param string $code
     *
     * @return Response
     */
    final public function showCalculator($code)
    {
        $this->checkParametersEmpty();

        $calculator = $this->getApiFacade()->getCalculator($code);
        return $this->getContentResponse($calculator);
    }

    /**
     * @param array  $territories
     * @param string $carrierCode
     *
     * @return void
     */
    private function createTerritories(array $territories, $carrierCode)
    {
        $countries = S\arrayGetValue($territories, CarrierSchema::ATTR_TERRITORIES_COUNTRIES, []);
        $regions   = S\arrayGetValue($territories, CarrierSchema::ATTR_TERRITORIES_REGIONS, []);

        $typeCountry = CarrierTerritoriesInterface::TERRITORY_TYPE_COUNTRY;
        foreach ($countries as $countryCode) {
            CarrierTerritories::create([
                CarrierTerritoriesInterface::PARAM_CARRIER_CODE   => $carrierCode,
                CarrierTerritoriesInterface::PARAM_TERRITORY_CODE => $countryCode,
                CarrierTerritoriesInterface::PARAM_TERRITORY_TYPE => $typeCountry,
            ]);
        }

        $typeRegion = CarrierTerritoriesInterface::TERRITORY_TYPE_REGION;
        foreach ($regions as $regionCode) {
            CarrierTerritories::create([
                CarrierTerritoriesInterface::PARAM_CARRIER_CODE   => $carrierCode,
                CarrierTerritoriesInterface::PARAM_TERRITORY_CODE => $regionCode,
                CarrierTerritoriesInterface::PARAM_TERRITORY_TYPE => $typeRegion,
            ]);
        }
    }

    /**
     * @param array  $postcodes
     * @param string $carrierCode
     *
     * @return void
     */
    private function createPostcodes(array $postcodes, $carrierCode)
    {
        foreach ($postcodes as $postcode) {
            $from = S\arrayGetValue($postcode, CarrierSchema::ATTR_POSTCODES_FROM);
            $to   = S\arrayGetValue($postcode, CarrierSchema::ATTR_POSTCODES_TO);
            $mask = S\arrayGetValue($postcode, CarrierSchema::ATTR_POSTCODES_MASK);

            CarrierPostcodes::create(S\arrayFilterNulls([
                CarrierPostcodesInterface::PARAM_CARRIER_CODE  => $carrierCode,
                CarrierPostcodesInterface::PARAM_POSTCODE_FROM => $from,
                CarrierPostcodesInterface::PARAM_POSTCODE_TO   => $to,
                CarrierPostcodesInterface::PARAM_POSTCODE_MASK => $mask,
            ]));
        }
    }

    /**
     * @param array  $customerTypes
     * @param string $carrierCode
     *
     * @return void
     */
    private function createCustomerTypes(array $customerTypes, $carrierCode)
    {
        foreach ($customerTypes as $customerTypeCode) {
            CarrierCustomerTypes::create(S\arrayFilterNulls([
                CarrierCustomerTypesInterface::PARAM_CARRIER_CODE  => $carrierCode,
                CarrierCustomerTypesInterface::PARAM_TYPE_CODE     => $customerTypeCode,
            ]));
        }
    }

    /**
     * @param Carrier $carrier
     *
     * @return void
     */
    private function deleteTerritories(Carrier $carrier)
    {
        foreach ($carrier->{Carrier::FIELD_TERRITORIES} as $territory) {
            /** @var BaseModel $territory */
            $territory->deleteOrFail();
        }
    }

    /**
     * @param Carrier $carrier
     *
     * @return void
     */
    private function deletePostcodes(Carrier $carrier)
    {
        foreach ($carrier->{Carrier::FIELD_POSTCODES} as $postcode) {
            /** @var BaseModel $postcode */
            $postcode->deleteOrFail();
        }
    }

    /**
     * @param Carrier $carrier
     *
     * @return void
     */
    private function deleteCustomerTypes(Carrier $carrier)
    {
        foreach ($carrier->{Carrier::FIELD_CUSTOMER_TYPES} as $customerType) {
            /** @var BaseModel $customerType */
            $customerType->deleteOrFail();
        }
    }
}
