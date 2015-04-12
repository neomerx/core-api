<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CarrierPostcode;
use \Illuminate\Database\Eloquent\Collection;

interface CarrierPostcodesInterface extends CrudInterface
{
    const PARAM_CARRIER_CODE  = 'carrier_code';
    const PARAM_POSTCODE_TO   = CarrierPostcode::FIELD_POSTCODE_TO;
    const PARAM_POSTCODE_FROM = CarrierPostcode::FIELD_POSTCODE_FROM;
    const PARAM_POSTCODE_MASK = CarrierPostcode::FIELD_POSTCODE_MASK;

    /**
     * Create carrier.
     *
     * @param array $input
     *
     * @return CarrierPostcode
     */
    public function create(array $input);

    /**
     * Read carrier by identifier.
     *
     * @param string $code
     *
     * @return CarrierPostcode
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
}
