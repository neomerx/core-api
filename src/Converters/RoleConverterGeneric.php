<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Support as S;

class RoleConverterGeneric implements ConverterInterface
{
    const BIND_NAME = __CLASS__;

    /**
     * Format model to array representation.
     *
     * @param Role $role
     *
     * @return array
     */
    public function convert($role = null)
    {
        if ($role === null) {
            return null;
        }

        assert('$role instanceof '.Role::class);

        $result = $role->attributesToArray();

        return $result;
    }
}
