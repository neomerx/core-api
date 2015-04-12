<?php namespace Neomerx\CoreApi\Api\Customers;

use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CustomerType;
use \Illuminate\Database\Eloquent\Collection;

interface CustomerTypesInterface extends CrudInterface
{
    const PARAM_CODE = CustomerType::FIELD_CODE;
    const PARAM_NAME = CustomerType::FIELD_NAME;

    /**
     * Create customer type.
     *
     * @param array $input
     *
     * @return CustomerType
     */
    public function create(array $input);

    /**
     * Read customer type by identifier.
     *
     * @param string $code
     *
     * @return CustomerType
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
