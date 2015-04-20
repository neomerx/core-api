<?php namespace Neomerx\CoreApi\Api\Stores;

use \Neomerx\Core\Models\Store;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface StoresInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_CODE       = Store::FIELD_CODE;
    /** Parameter key */
    const PARAM_NAME       = Store::FIELD_NAME;
    /** Parameter key */
    const PARAM_ID_ADDRESS = Store::FIELD_ID_ADDRESS;

    /**
     * Create image format.
     *
     * @param array $input
     *
     * @return Store
     */
    public function create(array $input);

    /**
     * Read image format by identifier.
     *
     * @param string $code
     *
     * @return Store
     */
    public function read($code);

    /**
     * Search image formats.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);

    /**
     * Get default store.
     *
     * @return Store
     */
    public function getDefault();
}
