<?php namespace Neomerx\CoreApi\Api\Customers;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CustomerRisk;
use \Illuminate\Database\Eloquent\Collection;

interface CustomerRisksInterface extends CrudInterface
{
    const PARAM_CODE = CustomerRisk::FIELD_CODE;
    const PARAM_NAME = CustomerRisk::FIELD_NAME;

    /**
     * Create customer type.
     *
     * @param array $input
     *
     * @return CustomerRisk
     */
    public function create(array $input);

    /**
     * Read customer type by identifier.
     *
     * @param string $code
     *
     * @return CustomerRisk
     */
    public function read($code);

    /**
     * Search customer types.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
