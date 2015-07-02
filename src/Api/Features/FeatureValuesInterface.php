<?php namespace Neomerx\CoreApi\Api\Features;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CharacteristicValue;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\Core\Models\CharacteristicValueProperties;

/**
 * @package Neomerx\CoreApi
 */
interface FeatureValuesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_CODE                = CharacteristicValue::FIELD_CODE;
    /** Parameter key */
    const PARAM_CHARACTERISTIC_CODE = 'characteristic_code';
    /** Parameter key */
    const PARAM_PROPERTIES          = CharacteristicValue::FIELD_PROPERTIES;
    /** Parameter key */
    const PARAM_PROPERTIES_VALUE    = CharacteristicValueProperties::FIELD_VALUE;

    /**
     * Create characteristic value.
     *
     * @param array $input
     *
     * @return CharacteristicValue
     */
    public function create(array $input);

    /**
     * Read characteristic value by identifier.
     *
     * @param string $code
     *
     * @return CharacteristicValue
     */
    public function read($code);

    /**
     * Search values.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
