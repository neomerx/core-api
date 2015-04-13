<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Models\RoleObjectType;
use \Neomerx\CoreApi\Api\Auth\RoleObjectTypesInterface as Api;

class RoleObjectTypeConverterGeneric implements ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param RoleObjectType $roleObjectType
     *
     * @return array
     */
    public function convert($roleObjectType = null)
    {
        if ($roleObjectType === null) {
            return null;
        }

        assert('$roleObjectType instanceof '.RoleObjectType::class);

        $result                       = $roleObjectType->attributesToArray();
        $result[Api::PARAM_ROLE_CODE] = $roleObjectType->{RoleObjectType::FIELD_ROLE}->{Role::FIELD_CODE};

        return $result;
    }
}
