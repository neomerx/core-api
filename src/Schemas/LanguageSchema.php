<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Language;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class LanguageSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'languages';

    /** Resources sub-URL */
    const SUB_URL = '/languages/';

    /** Schema attribute */
    const ATTR_NAME = Language::FIELD_NAME;

    /** Schema attribute */
    const ATTR_CREATED_AT = Language::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = Language::FIELD_UPDATED_AT;

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
    public function getId($language)
    {
        /** @var Language $language */
        return $language->{Language::FIELD_ISO_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($language)
    {
        /** @var Language $language */
        return [
            self::ATTR_NAME       => $language->{Language::FIELD_NAME},
            self::ATTR_CREATED_AT => $language->{Language::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT => $language->{Language::FIELD_UPDATED_AT},
        ];
    }
}
