<?php namespace Neomerx\CoreApi\Api\Currencies;

use \Neomerx\Core\Models\Currency;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CurrencyProperties;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface CurrenciesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_ID                     = Currency::FIELD_ID;
    /** Parameter key */
    const PARAM_CODE                   = Currency::FIELD_CODE;
    /** Parameter key */
    const PARAM_DECIMAL_DIGITS         = Currency::FIELD_DECIMAL_DIGITS;
    /** Parameter key */
    const PARAM_PROPERTIES             = Currency::FIELD_PROPERTIES;
    /** Parameter key */
    const PARAM_PROPERTIES_NAME        = CurrencyProperties::FIELD_NAME;
    /** Parameter key */
    const PARAM_PROPERTIES_DESCRIPTION = CurrencyProperties::FIELD_DESCRIPTION;

    /**
     * Create currency.
     *
     * @param array $input
     *
     * @return Currency
     */
    public function create(array $input);

    /**
     * Read currency by identifier.
     *
     * @param string $code
     *
     * @return Currency
     */
    public function read($code);

    /**
     * Search currencies.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
