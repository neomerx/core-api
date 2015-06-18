<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\ImageFormat;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class ImageFormatSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'image-formats';

    /** Resources sub-URL */
    const SUB_URL = '/image-formats/';

    /** Schema attribute */
    const ATTR_WIDTH = ImageFormat::FIELD_WIDTH;

    /** Schema attribute */
    const ATTR_HEIGHT = ImageFormat::FIELD_HEIGHT;

    /** Schema attribute */
    const ATTR_CREATED_AT = ImageFormat::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = ImageFormat::FIELD_UPDATED_AT;

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
    public function getId($format)
    {
        /** @var ImageFormat $format */
        return $format->{ImageFormat::FIELD_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($format)
    {
        /** @var ImageFormat $format */
        return [
            self::ATTR_WIDTH      => $format->{ImageFormat::FIELD_WIDTH},
            self::ATTR_HEIGHT     => $format->{ImageFormat::FIELD_HEIGHT},
            self::ATTR_CREATED_AT => $format->{ImageFormat::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT => $format->{ImageFormat::FIELD_UPDATED_AT},
        ];
    }
}
