<?php namespace Neomerx\CoreApi\Api\Features;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\Characteristic;
use \Illuminate\Database\Eloquent\Collection;
use \Neomerx\Core\Models\CharacteristicProperties;

/**
 * @package Neomerx\CoreApi
 */
interface CharacteristicsInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_CODE             = Characteristic::FIELD_CODE;
    /** Parameter key */
    const PARAM_MEASUREMENT_CODE = 'measurement_code';
    /** Parameter key */
    const PARAM_PROPERTIES       = Characteristic::FIELD_PROPERTIES;
    /** Parameter key */
    const PARAM_PROPERTIES_NAME  = CharacteristicProperties::FIELD_NAME;

    /**
     * Create characteristic.
     *
     * @param array $input
     *
     * @return Characteristic
     */
    public function create(array $input);

    /**
     * Read characteristic by identifier.
     *
     * @param string $code
     *
     * @return Characteristic
     */
    public function read($code);

    /**
     * Search characteristics.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);

    /**
     * Get all values for characteristic.
     *
     * @param string $characteristicCode
     *
     * @return Collection
     */
    public function getValues($characteristicCode);
}
