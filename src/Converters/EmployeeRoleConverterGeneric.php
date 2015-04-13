<?php namespace Neomerx\CoreApi\Converters;

use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Models\EmployeeRole;
use \Neomerx\CoreApi\Api\Employees\EmployeeRolesInterface as Api;

class EmployeeRoleConverterGeneric implements ConverterInterface
{
    /**
     * Format model to array representation.
     *
     * @param EmployeeRole $employeeRole
     *
     * @return array
     */
    public function convert($employeeRole = null)
    {
        if ($employeeRole === null) {
            return null;
        }

        assert('$employeeRole instanceof '.EmployeeRole::class);

        $result                       = $employeeRole->attributesToArray();
        $result[Api::PARAM_ROLE_CODE] = $employeeRole->{EmployeeRole::FIELD_ROLE}->{Role::FIELD_CODE};

        return $result;
    }
}
