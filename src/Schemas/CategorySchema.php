<?php namespace Neomerx\CoreApi\Schemas;

use \Neomerx\Core\Models\Category;
use \Neomerx\Core\Models\Language;
use \Neomerx\JsonApi\Schema\SchemaProvider;
use \Neomerx\Core\Models\CategoryProperties;

/**
 * @package Neomerx\CoreApi
 */
class CategorySchema extends SchemaProvider
{
    /** Root category code */
    const ROOT_CODE = Category::ROOT_CODE;

    /** JSON API type */
    const TYPE = 'categories';

    /** Resources sub-URL */
    const SUB_URL = '/categories/';

    /** Schema attribute */
    const ATTR_LINK = Category::FIELD_LINK;

    /** Schema attribute */
    const ATTR_ENABLED = Category::FIELD_ENABLED;

    /** Schema attribute */
    const ATTR_NUMBER_OF_DESCENDANTS = Category::FIELD_NUMBER_OF_DESCENDANTS;

    /** Schema attribute */
    const ATTR_PROPERTIES = Category::FIELD_PROPERTIES;

    /** Schema attribute */
    const ATTR_CREATED_AT = Category::FIELD_CREATED_AT;

    /** Schema attribute */
    const ATTR_UPDATED_AT = Category::FIELD_UPDATED_AT;

    /** Schema relationship */
    const REL_ANCESTOR = Category::FIELD_ANCESTOR;

    /** Schema relationship */
    const REL_DESCENDANTS = 'descendants';

    /** Schema relationship */
    const REL_PRODUCTS = Category::FIELD_PRODUCTS;

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
    public function getId($category)
    {
        /** @var Category $category */
        return $category->{Category::FIELD_CODE};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($category)
    {
        $properties = [];
        foreach ($category->{Category::FIELD_PROPERTIES} as $property) {
            $isoCode = $property->{CategoryProperties::FIELD_LANGUAGE}->{Language::FIELD_ISO_CODE};
            $properties[$isoCode] = [
                CategoryProperties::FIELD_NAME        => $property->{CategoryProperties::FIELD_NAME},
                CategoryProperties::FIELD_DESCRIPTION => $property->{CategoryProperties::FIELD_DESCRIPTION},
            ];
        }

        /** @var Category $category */
        return [
            self::ATTR_LINK       => $category->{Category::FIELD_LINK},
            self::ATTR_ENABLED    => $category->{Category::FIELD_ENABLED},
            self::ATTR_PROPERTIES => $properties,
            self::ATTR_CREATED_AT => $category->{Category::FIELD_CREATED_AT},
            self::ATTR_UPDATED_AT => $category->{Category::FIELD_UPDATED_AT},
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($category)
    {
        /** @var Category $category */
        return [
            self::REL_ANCESTOR    => [self::DATA => $category->{Category::FIELD_ANCESTOR}],
            self::REL_DESCENDANTS => [self::DATA => $category->getDescendants()->all()],

            // TODO add relationship to products
        ];
    }
}
