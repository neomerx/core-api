<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Role;
use \Neomerx\Core\Models\Employee;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class EmployeeSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'employees';

    /** Resources sub-URL */
    const SUB_URL = '/employees/';

    /** Schema attribute */
    const ATTR_FIRST_NAME = Employee::FIELD_FIRST_NAME;

    /** Schema attribute */
    const ATTR_LAST_NAME = Employee::FIELD_LAST_NAME;

    /** Schema attribute */
    const ATTR_EMAIL = Employee::FIELD_EMAIL;

    /** Schema relationship */
    const ATTR_PASSWORD = Employee::FIELD_PASSWORD;

    /** Schema relationship */
    const ATTR_ACTIVE = Employee::FIELD_ACTIVE;

    /** Schema relationship */
    const ATTR_REMEMBER_TOKEN = Employee::FIELD_REMEMBER_TOKEN;

    /** Schema relationship */
    const ATTR_ROLES = Employee::FIELD_ROLES;

    /**
     * @var string
     */
    protected $resourceType = self::TYPE;

    /**
     * @var string
     */
    protected $selfSubUrl = self::SUB_URL;

    /**
     * @inheritdoc
     */
    public function getId($employee)
    {
        /** @var Employee $employee */
        return $employee->{Employee::FIELD_ID};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($employee)
    {
        /** @var Employee $employee */

        $roles = [];
        foreach ($employee->{Employee::FIELD_ROLES} as $role) {
            $roles[] = $role->{Role::FIELD_CODE};
        }

        return [
            self::ATTR_FIRST_NAME => $employee->{Employee::FIELD_FIRST_NAME},
            self::ATTR_LAST_NAME  => $employee->{Employee::FIELD_LAST_NAME},
            self::ATTR_EMAIL      => $employee->{Employee::FIELD_EMAIL},
            self::ATTR_ACTIVE     => $employee->{Employee::FIELD_ACTIVE},
            self::ATTR_ROLES      => $roles,
        ];
    }
}
