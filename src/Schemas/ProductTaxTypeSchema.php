<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\ProductTaxType;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class ProductTaxTypeSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'product-tax-types';

    /** Resources sub-URL */
    const SUB_URL = '/product-tax-types/';

    /** Schema attribute */
    const ATTR_NAME = ProductTaxType::FIELD_NAME;

    /** Schema attribute */
    const ATTR_CREATED_AT = ProductTaxType::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = ProductTaxType::FIELD_UPDATED_AT;

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
    public function getId($taxType)
    {
        /** @var ProductTaxType $taxType */
        return $taxType->{ProductTaxType::FIELD_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($taxType)
    {
        /** @var ProductTaxType $taxType */
        return [
            self::ATTR_NAME       => $taxType->{ProductTaxType::FIELD_NAME},
            self::ATTR_CREATED_AT => $taxType->{ProductTaxType::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT => $taxType->{ProductTaxType::FIELD_UPDATED_AT},
        ];
    }
}
