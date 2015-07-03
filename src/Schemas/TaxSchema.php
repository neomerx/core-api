<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Tax;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class TaxSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'taxes';

    /** Resources sub-URL */
    const SUB_URL = '/taxes/';

    /** Schema attribute */
    const ATTR_EXPRESSION = Tax::FIELD_EXPRESSION;

    /** Schema relationship */
    const REL_RULES = Tax::FIELD_RULES;

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
    public function getId($tax)
    {
        /** @var Tax $tax */
        return $tax->{Tax::FIELD_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($tax)
    {
        /** @var Tax $tax */
        return [
            self::ATTR_EXPRESSION => $tax->{Tax::FIELD_EXPRESSION},
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($tax)
    {
        /** @var Tax $tax */
        return [
            self::REL_RULES => [self::DATA => $tax->{Tax::FIELD_RULES}->all(), self::INCLUDED => true],
        ];
    }
}
