<?php namespace Neomerx\CoreApi\Api\Warehouses;

use \Neomerx\Core\Models\Warehouse;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Illuminate\Database\Eloquent\Collection;

interface WarehousesInterface extends CrudInterface
{
    const PARAM_CODE       = Warehouse::FIELD_CODE;
    const PARAM_NAME       = Warehouse::FIELD_NAME;
    const PARAM_ID_ADDRESS = Warehouse::FIELD_ID_ADDRESS;
    const PARAM_STORE_CODE = 'store_code';

    /**
     * Create warehouse.
     *
     * @param array $input
     *
     * @return Warehouse
     */
    public function create(array $input);

    /**
     * Read warehouse by identifier.
     *
     * @param string $code
     *
     * @return Warehouse
     */
    public function read($code);

    /**
     * Search warehouses.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);

    /**
     * Get default warehouse.
     *
     * @return Warehouse
     */
    public function getDefault();
}
