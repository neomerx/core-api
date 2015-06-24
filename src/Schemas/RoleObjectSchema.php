<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\RoleObjectType;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class RoleObjectSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'role-objects';

    /** Resources sub-URL */
    const SUB_URL = '/role-objects/';

    /** Schema attribute */
    const ATTR_ALLOW_MASK = RoleObjectType::FIELD_ALLOW_MASK;

    /** Schema attribute */
    const ATTR_DENY_MASK = RoleObjectType::FIELD_DENY_MASK;

    /** Schema relationship */
    const REL_ROLE = RoleObjectType::FIELD_ROLE;

    /** Schema relationship */
    const REL_OBJECT_TYPE = 'object_type';

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
    public function getId($roleObjectType)
    {
        /** @var RoleObjectType $roleObjectType */
        return $roleObjectType->{RoleObjectType::FIELD_ID};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($roleObjectType)
    {
        /** @var RoleObjectType $roleObjectType */
        return [
            self::ATTR_ALLOW_MASK => $roleObjectType->{RoleObjectType::FIELD_ALLOW_MASK},
            self::ATTR_DENY_MASK  => $roleObjectType->{RoleObjectType::FIELD_DENY_MASK},
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($roleObjectType)
    {
        /** @var RoleObjectType $roleObjectType */
        return [
            self::REL_ROLE        => [self::DATA => $roleObjectType->{RoleObjectType::FIELD_ROLE}],
            self::REL_OBJECT_TYPE => [self::DATA => $roleObjectType->{RoleObjectType::FIELD_TYPE}],
        ];
    }
}
