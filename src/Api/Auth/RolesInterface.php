<?php namespace Neomerx\CoreApi\Api\Auth;

use \Neomerx\Core\Models\Role;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface RolesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_CODE        = Role::FIELD_CODE;
    /** Parameter key */
    const PARAM_DESCRIPTION = Role::FIELD_DESCRIPTION;

    /**
     * Create role.
     *
     * @param array $input
     *
     * @return Role
     */
    public function create(array $input);

    /**
     * Read role by identifier.
     *
     * @param string $code
     *
     * @return Role
     */
    public function read($code);

    /**
     * Search roles.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters);
}
