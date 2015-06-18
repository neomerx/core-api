<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Image;
use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\ImagePath;
use \Neomerx\Core\Models\ImageFormat;
use \Neomerx\Core\Models\ImageProperties;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class ImageSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'images';

    /** Resources sub-URL */
    const SUB_URL = '/images/';

    /** Schema attribute */
    const ATTR_PATHS     = Image::FIELD_PATHS;

    /** Schema attribute */
    const ATTR_PROPERTIES = Image::FIELD_PROPERTIES;

    /** Schema attribute */
    const ATTR_PROPERTIES_ALT = ImageProperties::FIELD_ALT;

    /** Schema attribute */
    const PATHS_WIDTH = ImageFormat::FIELD_WIDTH;

    /** Schema attribute */
    const PATHS_PATH = ImagePath::FIELD_PATH;

    /** Schema attribute */
    const PATHS_HEIGHT = ImageFormat::FIELD_HEIGHT;

    /** Schema attribute */
    const ATTR_CREATED_AT = Image::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = Image::FIELD_UPDATED_AT;

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
    public function getId($image)
    {
        /** @var Image $image */
        return $image->{Image::FIELD_ID};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($image)
    {
        $properties = [];
        foreach ($image->{Image::FIELD_PROPERTIES} as $property) {
            $isoCode = $property->{ImageProperties::FIELD_LANGUAGE}->{Language::FIELD_ISO_CODE};
            $properties[$isoCode] = [
                self::ATTR_PROPERTIES_ALT => $property->{ImageProperties::FIELD_ALT},
            ];
        }

        $paths = [];
        foreach ($image->{Image::FIELD_PATHS} as $path) {
            $format = $path->{ImagePath::FIELD_FORMAT};
            $paths[$format->{ImageFormat::FIELD_CODE}] = [
                self::PATHS_PATH   => $path->{ImagePath::FIELD_PATH},
                self::PATHS_WIDTH  => $format->{ImageFormat::FIELD_WIDTH},
                self::PATHS_HEIGHT => $format->{ImageFormat::FIELD_HEIGHT},
            ];
        }

        /** @var Image $image */
        return [
            self::ATTR_PATHS      => $paths,
            self::ATTR_PROPERTIES => $properties,
            self::ATTR_CREATED_AT => $image->{Image::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT => $image->{Image::FIELD_UPDATED_AT},
        ];
    }
}
