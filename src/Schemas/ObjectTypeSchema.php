<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\ObjectType;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class ObjectTypeSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'object-types';

    /** Resources sub-URL */
    const SUB_URL = '/object-types/';

    /** Schema attribute */
    const ATTR_OBJECT_TYPE = 'object_type';

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
    public function getId($objectType)
    {
        /** @var ObjectType $objectType */
        return $objectType->{ObjectType::FIELD_ID};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($objectType)
    {
        /** @var ObjectType $objectType */
        return [
            self::ATTR_OBJECT_TYPE => $objectType->{ObjectType::FIELD_TYPE},
        ];
    }
}
