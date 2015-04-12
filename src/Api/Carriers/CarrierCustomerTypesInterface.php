<?php namespace Neomerx\CoreApi\Api\Carriers;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CarrierCustomerType;
use \Illuminate\Database\Eloquent\Collection;

interface CarrierCustomerTypesInterface extends CrudInterface
{
    const PARAM_TYPE_CODE    = 'type_code';
    const PARAM_CARRIER_CODE = 'carrier_code';

    const ALL_TYPE_CODES  = '*';

    /**
     * Create carrier.
     *
     * @param array $input
     *
     * @return CarrierCustomerType
     */
    public function create(array $input);

    /**
     * Read carrier by identifier.
     *
     * @param string $code
     *
     * @return CarrierCustomerType
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
