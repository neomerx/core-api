<?php namespace Neomerx\CoreApi\Api\Categories;

use \Neomerx\Core\Models\Category;
use \Neomerx\CoreApi\Events\EventArgs;

/**
 * @package Neomerx\CoreApi
 */
class CategoryArgs extends EventArgs
{
    /**
     * @var Category
     */
    private $category;

    /**
     * @param string    $name
     * @param Category  $category
     * @param EventArgs $args
     */
    public function __construct($name, Category $category, EventArgs $args = null)
    {
        parent::__construct($name, $args);
        $this->category = $category;
    }

    /**
     * @return Category
     */
    public function getModel()
    {
        return $this->category;
    }
}
