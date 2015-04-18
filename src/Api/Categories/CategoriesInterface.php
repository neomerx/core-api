<?php namespace Neomerx\CoreApi\Api\Categories;

use \Neomerx\Core\Models\Category;
use \Neomerx\CoreApi\Api\CrudInterface;
use \Neomerx\Core\Models\CategoryProperties;
use \Illuminate\Database\Eloquent\Collection;

/**
 * @package Neomerx\CoreApi
 */
interface CategoriesInterface extends CrudInterface
{
    /** Parameter key */
    const PARAM_CODE                        = Category::FIELD_CODE;
    /** Parameter key */
    const PARAM_LINK                        = Category::FIELD_LINK;
    /** Parameter key */
    const PARAM_ENABLED                     = Category::FIELD_ENABLED;
    /** Parameter key */
    const PARAM_PROPERTIES                  = Category::FIELD_PROPERTIES;
    /** Parameter key */
    const PARAM_PROPERTIES_NAME             = CategoryProperties::FIELD_NAME;
    /** Parameter key */
    const PARAM_PROPERTIES_DESCRIPTION      = CategoryProperties::FIELD_DESCRIPTION;
    /** Parameter key */
    const PARAM_PROPERTIES_META_TITLE       = CategoryProperties::FIELD_META_TITLE;
    /** Parameter key */
    const PARAM_PROPERTIES_META_KEYWORDS    = CategoryProperties::FIELD_META_KEYWORDS;
    /** Parameter key */
    const PARAM_PROPERTIES_META_DESCRIPTION = CategoryProperties::FIELD_META_DESCRIPTION;

    /**
     * Create category.
     *
     * @param array $input
     *
     * @return Category
     */
    public function create(array $input);

    /**
     * Read category by identifier.
     *
     * @param string $code
     *
     * @return Category
     */
    public function read($code);

    /**
     * Read descendant categories.
     *
     * @param string $parentCode
     *
     * @return Collection
     */
    public function readDescendants($parentCode);

    /**
     * Switch place of the category with upper category of the same parent.
     *
     * @param string $categoryCode
     *
     * @return void
     */
    public function moveUp($categoryCode);

    /**
     * Switch place of the category with lower category of the same parent.
     *
     * @param string $categoryCode
     *
     * @return void
     */
    public function moveDown($categoryCode);

    /**
     * Attach category with code $code to category with code $attachTo.
     *
     * @param string $categoryCode
     * @param string $newParentCode
     *
     * @return void
     */
    public function attach($categoryCode, $newParentCode);

    /**
     * Read descendant categories.
     *
     * @param Category $parent
     *
     * @return Collection
     */
    public function readDescendantsByCategory(Category $parent);

    /**
     * Switch place of the category with upper category of the same parent.
     *
     * @param Category $category
     *
     * @return void
     */
    public function moveUpByCategory(Category $category);

    /**
     * Switch place of the category with lower category of the same parent.
     *
     * @param Category $category
     *
     * @return void
     */
    public function moveDownByCategory(Category $category);

    /**
     * Attach category with code $code to category with code $attachTo.
     *
     * @param Category $category
     * @param Category $newParent
     *
     * @return void
     */
    public function attachByCategory(Category $category, Category $newParent);

//    /**
//     * Read products in category.
//     *
//     * @param Category $category
//     *
//     * @return array Array of pairs [$product, $positionInCategory]
//     */
//    public function showProducts(Category $category);
//
//    /**
//     * @param Category $category
//     * @param array    $productPositions Product code and position pairs.
//     *
//     * @return void
//     */
//    public function updatePositions(Category $category, array $productPositions);
}
