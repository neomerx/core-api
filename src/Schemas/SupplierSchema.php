<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Language;
use \Neomerx\Core\Models\Supplier;
use \Neomerx\JsonApi\Schema\SchemaProvider;
use \Neomerx\Core\Models\SupplierProperties;

/**
 * @package Neomerx\CoreApi
 */
class SupplierSchema extends SchemaProvider
{
    /** JSON API type */
    const TYPE = 'suppliers';

    /** Resources sub-URL */
    const SUB_URL = '/suppliers/';

    /** Schema attribute */
    const ATTR_PROPERTIES = Supplier::FIELD_PROPERTIES;

    /** Schema attribute */
    const ATTR_CREATED_AT = Supplier::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = Supplier::FIELD_UPDATED_AT;

    /** Schema relationship */
    const REL_ADDRESS = Supplier::FIELD_ADDRESS;

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
    public function getId($supplier)
    {
        /** @var Supplier $supplier */
        return $supplier->{Supplier::FIELD_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($supplier)
    {
        $attributes = [];
        foreach ($supplier->{Supplier::FIELD_PROPERTIES} as $property) {
            $isoCode = $property->{SupplierProperties::FIELD_LANGUAGE}->{Language::FIELD_ISO_CODE};
            $attributes[$isoCode] = [
                SupplierProperties::FIELD_NAME        => $property->{SupplierProperties::FIELD_NAME},
                SupplierProperties::FIELD_DESCRIPTION => $property->{SupplierProperties::FIELD_DESCRIPTION},
            ];
        }

        /** @var Supplier $supplier */
        return [
            self::ATTR_PROPERTIES => $attributes,
            self::ATTR_CREATED_AT => $supplier->{Supplier::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT => $supplier->{Supplier::FIELD_UPDATED_AT},
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($supplier)
    {
        /** @var Supplier $supplier */
        return [
            self::REL_ADDRESS => [self::DATA => $supplier->{Supplier::FIELD_ADDRESS}],
        ];
    }
}
