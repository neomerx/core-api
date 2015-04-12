<?php namespace Neomerx\CoreApi\Api\Suppliers;

use \Neomerx\Core\Models\Supplier;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\SupplierProperties;
use \Illuminate\Database\Eloquent\Collection;

interface SuppliersInterface extends CrudInterface
{
    const PARAM_CODE                   = Supplier::FIELD_CODE;
    const PARAM_ADDRESS                = Supplier::FIELD_ADDRESS;
    const PARAM_PROPERTIES             = Supplier::FIELD_PROPERTIES;
    const PARAM_PROPERTIES_NAME        = SupplierProperties::FIELD_NAME;
    const PARAM_PROPERTIES_DESCRIPTION = SupplierProperties::FIELD_DESCRIPTION;

    /**
     * Create supplier.
     *
     * @param array $input
     *
     * @return Supplier
     */
    public function create(array $input);

    /**
     * Read supplier by identifier.
     *
     * @param string $code
     *
     * @return Supplier
     */
    public function read($code);

    /**
     * Search suppliers.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
