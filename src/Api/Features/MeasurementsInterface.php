<?php namespace Neomerx\CoreApi\Api\Features;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\Measurement;
use \Illuminate\Database\Eloquent\Collection;
use Neomerx\Core\Models\MeasurementProperties;

/**
 * @package Neomerx\CoreApi
 */
interface MeasurementsInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_CODE            = Measurement::FIELD_CODE;
    /** Parameter key */
    const PARAM_PROPERTIES      = Measurement::FIELD_PROPERTIES;
    /** Parameter key */
    const PARAM_PROPERTIES_NAME = MeasurementProperties::FIELD_NAME;

    /**
     * Create measurement.
     *
     * @param array $input
     *
     * @return Measurement
     */
    public function create(array $input);

    /**
     * Read measurement by identifier.
     *
     * @param string $code
     *
     * @return Measurement
     */
    public function read($code);

    /**
     * Search measurement units.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
