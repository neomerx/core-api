<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\Core\Models\Carrier;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CarrierProperties;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\CoreApi\Api\Carriers\Calculators\Calculator;

/**
 * @package Neomerx\CoreApi
 */
interface CarriersInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_CODE                   = Carrier::FIELD_CODE;
    /** Parameter key */
    const PARAM_SETTINGS               = Carrier::FIELD_SETTINGS;
    // TODO rename FIELD_FACTORY to calculator code
    /** Parameter key */
    const PARAM_CALCULATOR_CODE        = Carrier::FIELD_CALCULATOR_CODE;
     /** Parameter key */
    const PARAM_IS_TAXABLE             = Carrier::FIELD_IS_TAXABLE;
     /** Parameter key */
    const PARAM_MIN_WEIGHT             = Carrier::FIELD_MIN_WEIGHT;
     /** Parameter key */
    const PARAM_MAX_WEIGHT             = Carrier::FIELD_MAX_WEIGHT;
     /** Parameter key */
    const PARAM_MIN_COST               = Carrier::FIELD_MIN_COST;
     /** Parameter key */
    const PARAM_MAX_COST               = Carrier::FIELD_MAX_COST;
     /** Parameter key */
    const PARAM_MIN_DIMENSION          = Carrier::FIELD_MIN_DIMENSION;
     /** Parameter key */
    const PARAM_MAX_DIMENSION          = Carrier::FIELD_MAX_DIMENSION;
     /** Parameter key */
    const PARAM_PROPERTIES             = Carrier::FIELD_PROPERTIES;
     /** Parameter key */
    const PARAM_PROPERTIES_NAME        = CarrierProperties::FIELD_NAME;
    /** Parameter key */
   const PARAM_PROPERTIES_DESCRIPTION = CarrierProperties::FIELD_DESCRIPTION;

    /**
     * Create carrier.
     *
     * @param array $input
     *
     * @return Carrier
     */
    public function create(array $input);

    /**
     * Read carrier by identifier.
     *
     * @param string $code
     *
     * @return Carrier
     */
    public function read($code);

    /**
     * Search carriers.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);

    /**
     * @param ShippingData $shippingData
     * @param Carrier      $carrier
     *
     * @return Tariff
     */
    public function calculateTariff(ShippingData $shippingData, Carrier $carrier);

    /**
     * @param ShippingData $shippingData
     *
     * @return array
     */
    public function calculateTariffs(ShippingData $shippingData);

    /**
     * Get available carrier calculators.
     *
     * @return array
     */
    public function getAvailableCalculators();

    /**
     * Get calculator.
     *
     * @param string $code
     *
     * @return Calculator|null
     */
    public function getCalculator($code);
}
