<?php namespace Neomerx\CoreApi\Api\Auth;

use \Neomerx\Core\Models\ObjectType;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface ObjectTypesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_ID   = ObjectType::FIELD_ID;
    /** Parameter key */
    const PARAM_TYPE = ObjectType::FIELD_TYPE;

    /**
     * Create object type.
     *
     * @param array $input
     *
     * @return ObjectType
     */
    public function create(array $input);

    /**
     * Read object type by identifier.
     *
     * @param string $code
     *
     * @return ObjectType
     */
    public function read($code);

    /**
     * Search object types.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
