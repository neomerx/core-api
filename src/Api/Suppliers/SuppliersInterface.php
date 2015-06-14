<?php namespace Neomerx\CoreApi\Api\Suppliers;

use \Neomerx\Core\Models\Supplier;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\SupplierProperties;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface SuppliersInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_CODE                   = Supplier::FIELD_CODE;
    /** Parameter key */
    const PARAM_ID_ADDRESS             = Supplier::FIELD_ID_ADDRESS;
    /** Parameter key */
    const PARAM_PROPERTIES             = Supplier::FIELD_PROPERTIES;
    /** Parameter key */
    const PARAM_PROPERTIES_NAME        = SupplierProperties::FIELD_NAME;
    /** Parameter key */
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
