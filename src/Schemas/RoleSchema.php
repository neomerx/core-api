<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Role;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class RoleSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'roles';

    /** Resources sub-URL */
    const SUB_URL = '/roles/';

    /** Schema attribute */
    const ATTR_DESCRIPTION = Role::FIELD_DESCRIPTION;

    /** Schema relationship */
    const REL_ROLE_OBJECTS = 'role-objects';

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
    public function getId($role)
    {
        /** @var Role $role */
        return $role->{Role::FIELD_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($role)
    {
        /** @var Role $role */
        return [
            self::ATTR_DESCRIPTION => $role->{Role::FIELD_DESCRIPTION},
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($role)
    {
        /** @var Role $role */
        return [
            self::REL_ROLE_OBJECTS => [
                self::DATA     => $role->{Role::FIELD_ROLE_OBJECT_TYPES}->all(),
                self::INCLUDED => true,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getIncludePaths()
    {
        return [
            self::REL_ROLE_OBJECTS,
            self::REL_ROLE_OBJECTS . '.' . RoleObjectSchema::REL_OBJECT_TYPE,
        ];
    }
}
