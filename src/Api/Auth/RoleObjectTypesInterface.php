<?php namespace Neomerx\CoreApi\Api\Auth;

use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Models\RoleObjectType;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface RoleObjectTypesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_ID         = RoleObjectType::FIELD_ID;
    /** Parameter key */
    const PARAM_ID_ROLE    = RoleObjectType::FIELD_ID_ROLE;
    /** Parameter key */
    const PARAM_ID_TYPE    = RoleObjectType::FIELD_ID_TYPE;
    /** Parameter key */
    const PARAM_ALLOW_MASK = RoleObjectType::FIELD_ALLOW_MASK;
    /** Parameter key */
    const PARAM_DENY_MASK  = RoleObjectType::FIELD_DENY_MASK;
    /** Parameter key */
    const PARAM_ROLE_CODE  = 'role_code';

    /**
     * Create role object type link.
     *
     * @param array $input
     *
     * @return RoleObjectType
     */
    public function create(array $input);

    /**
     * Read role object type link by identifier.
     *
     * @param int $idx
     *
     * @return RoleObjectType
     */
    public function read($idx);

    /**
     * Create role object type link.
     *
     * @param Role  $role
     * @param array $input
     *
     * @return RoleObjectType
     */
    public function createWithRole(Role $role, array $input);

    /**
     * Read role object type link by identifier.
     *
     * @param string $roleCode
     * @param int    $roleObjectTypeId
     *
     * @return RoleObjectType
     */
    public function readWithRole($roleCode, $roleObjectTypeId);

    /**
     * Delete role object type link by identifier.
     *
     * @param string $roleCode
     * @param int    $roleObjectTypeId
     *
     * @return void
     */
    public function deleteWithRole($roleCode, $roleObjectTypeId);

    /**
     * Search role object type links.
     *
     * @param array $parameters
     *
     * @return Collection
     */
    public function search(array $parameters = []);
}
