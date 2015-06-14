<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Region;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class RegionSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'regions';

    /** Resources sub-URL */
    const SUB_URL = '/regions/';

    /** Schema attribute */
    const ATTR_NAME = Region::FIELD_NAME;

    /** Schema attribute */
    const ATTR_POSITION = Region::FIELD_POSITION;

    /** Schema attribute */
    const ATTR_CREATED_AT = Region::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = Region::FIELD_UPDATED_AT;

    /** Schema relationship */
    const REL_COUNTRY = Region::FIELD_COUNTRY;

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
    public function getId($region)
    {
        /** @var Region $region */
        return $region->{Region::FIELD_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($region)
    {
        /** @var Region $region */
        return [
            self::ATTR_NAME       => $region->{Region::FIELD_NAME},
            self::ATTR_POSITION   => $region->{Region::FIELD_POSITION},
            self::ATTR_CREATED_AT => $region->{Region::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT => $region->{Region::FIELD_UPDATED_AT},
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($region)
    {
        /** @var Region $region */
        return [
            self::REL_COUNTRY => [self::DATA => $region->{Region::FIELD_COUNTRY}],
        ];
    }
}
