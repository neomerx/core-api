<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\CustomerType;
use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\CoreApi
 */
class CustomerTypeSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'customer-types';

    /** Resources sub-URL */
    const SUB_URL = '/customer-types/';

    /** Schema attribute */
    const ATTR_NAME = CustomerType::FIELD_NAME;

    /** Schema attribute */
    const ATTR_CREATED_AT = CustomerType::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = CustomerType::FIELD_UPDATED_AT;

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
    public function getId($customerType)
    {
        /** @var CustomerType $customerType */
        return $customerType->{CustomerType::FIELD_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($customerType)
    {
        /** @var CustomerType $customerType */
        return [
            self::ATTR_NAME       => $customerType->{CustomerType::FIELD_NAME},
            self::ATTR_CREATED_AT => $customerType->{CustomerType::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT => $customerType->{CustomerType::FIELD_UPDATED_AT},
        ];
    }
}
