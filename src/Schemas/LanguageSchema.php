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

    /** Resources URL */
    const BASE_URL = '/languages';

    /** Schema attribute */
    const ATTR_NAME = Language::FIELD_NAME;

    /** Schema attribute */
    const ATTR_ISO_CODE = Language::FIELD_ISO_CODE;

    /**
     * @var string
     */
    protected $resourceType = self::TYPE;

    /**
     * @var string
     */
    protected $baseSelfUrl = self::BASE_URL;

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
            self::ATTR_NAME => $language->{Language::FIELD_NAME},
        ];
    }
}
